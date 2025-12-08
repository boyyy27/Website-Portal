<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Transaction;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction as MidtransTransaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Show checkout page
     */
    public function checkout($packageId)
    {
        $user = Auth::user();
        $package = Package::findOrFail($packageId);

        // Check if package is active
        if (!$package->is_active) {
            return redirect()->route('landing')
                ->with('error', 'Paket tidak tersedia');
        }

        return view('payment.checkout', compact('package', 'user'));
    }

    /**
     * Create payment transaction
     */
    public function createTransaction(Request $request, $packageId)
    {
        $user = Auth::user();
        $package = Package::findOrFail($packageId);

        // Validate request
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
        ]);

        // Check if package is active
        if (!$package->is_active) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Paket tidak tersedia');
        }

        // Generate unique order ID
        $orderId = 'ORDER-' . time() . '-' . $user->id . '-' . $package->id;

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'transaction_status' => 'pending',
            'gross_amount' => $package->price,
            'currency' => 'IDR',
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
        ]);

        // Prepare Midtrans transaction parameters
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $package->price,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => $package->name,
                ],
            ],
        ];

        try {
            // Create Snap token
            $snapToken = Snap::getSnapToken($transactionDetails);

            // Update transaction with snap token
            $transaction->update([
                'midtrans_response' => ['snap_token' => $snapToken],
            ]);

            return view('payment.payment', [
                'transaction' => $transaction,
                'snapToken' => $snapToken,
                'clientKey' => config('services.midtrans.client_key'),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap token creation failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(Request $request)
    {
        try {
            // Log raw request for debugging
            Log::info('Midtrans notification received (raw)', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            $notification = new Notification();

            $orderId = $notification->order_id;
            
            if (!$orderId) {
                Log::error('Notification missing order_id', [
                    'notification' => $notification->getResponse(),
                ]);
                return response()->json(['status' => 'error', 'message' => 'Missing order_id'], 400);
            }

            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                Log::warning('Transaction not found for notification', [
                    'order_id' => $orderId,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            // Log notification details
            Log::info('Midtrans notification processed', [
                'order_id' => $orderId,
                'transaction_id' => $notification->transaction_id,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $notification->payment_type,
                'current_db_status' => $transaction->transaction_status,
            ]);

            // Update transaction
            $updateData = [
                'transaction_id' => $notification->transaction_id,
                'transaction_status' => $transactionStatus,
                'payment_type' => $notification->payment_type ?? null,
                'payment_method' => $notification->payment_type ?? null,
                'transaction_time' => isset($notification->transaction_time) ? date('Y-m-d H:i:s', strtotime($notification->transaction_time)) : null,
                'settlement_time' => isset($notification->settlement_time) ? date('Y-m-d H:i:s', strtotime($notification->settlement_time)) : null,
                'fraud_status' => $fraudStatus,
                'midtrans_response' => $notification->getResponse(),
                'notification_received' => true,
                'notification_count' => $transaction->notification_count + 1,
                'last_notification_at' => now(),
            ];

            $transaction->update($updateData);
            
            // Reload transaction to get updated data
            $transaction->refresh();

            Log::info('Transaction updated', [
                'transaction_id' => $transaction->id,
                'new_status' => $transaction->transaction_status,
            ]);

            // Handle successful payment
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Check fraud status - accept or empty (some payment methods don't have fraud status)
                if (empty($fraudStatus) || $fraudStatus == 'accept') {
                    // Check if user package already exists
                    $userPackage = UserPackage::where('transaction_id', $transaction->id)->first();

                    if (!$userPackage) {
                        // Reload transaction with package relationship
                        $transaction->load('package');
                        
                        if (!$transaction->package && $transaction->package_id) {
                            // Try to load package directly
                            $package = \App\Models\Package::find($transaction->package_id);
                            if ($package) {
                                $transaction->setRelation('package', $package);
                            }
                        }
                        
                        // Create user package subscription
                        $startDate = now();
                        $durationDays = 30; // Default
                        
                        if ($transaction->package) {
                            $durationDays = $transaction->package->duration_days;
                        } elseif ($transaction->package_id) {
                            $package = \App\Models\Package::find($transaction->package_id);
                            if ($package) {
                                $durationDays = $package->duration_days;
                            }
                        }
                        
                        $endDate = now()->addDays($durationDays);

                        // Deactivate other active packages for this user
                        UserPackage::where('user_id', $transaction->user_id)
                            ->where('is_active', true)
                            ->update(['is_active' => false]);

                        $userPackage = UserPackage::create([
                            'user_id' => $transaction->user_id,
                            'package_id' => $transaction->package_id,
                            'transaction_id' => $transaction->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'is_active' => true,
                        ]);

                        Log::info('User package created successfully', [
                            'user_id' => $transaction->user_id,
                            'package_id' => $transaction->package_id,
                            'transaction_id' => $transaction->id,
                            'user_package_id' => $userPackage->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ]);
                    } else {
                        Log::info('User package already exists', [
                            'user_package_id' => $userPackage->id,
                            'transaction_id' => $transaction->id,
                        ]);
                    }
                } else {
                    Log::warning('Payment successful but fraud status not accepted', [
                        'fraud_status' => $fraudStatus,
                        'transaction_status' => $transactionStatus,
                        'order_id' => $orderId,
                    ]);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check transaction status manually from Midtrans
     */
    public function checkStatus($orderId)
    {
        try {
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            // Use Midtrans API to check status
            $status = MidtransTransaction::status($orderId);

            Log::info('Manual status check from Midtrans', [
                'order_id' => $orderId,
                'midtrans_status' => $status->transaction_status,
                'current_db_status' => $transaction->transaction_status,
            ]);

            // Update transaction with latest status
            $updateData = [
                'transaction_id' => $status->transaction_id ?? $transaction->transaction_id,
                'transaction_status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'payment_method' => $status->payment_type ?? null,
                'transaction_time' => isset($status->transaction_time) ? date('Y-m-d H:i:s', strtotime($status->transaction_time)) : null,
                'settlement_time' => isset($status->settlement_time) ? date('Y-m-d H:i:s', strtotime($status->settlement_time)) : null,
                'fraud_status' => $status->fraud_status ?? null,
                'midtrans_response' => (array) $status,
            ];

            $transaction->update($updateData);
            $transaction->refresh();

            // Handle successful payment (same logic as notification)
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $fraudStatus = $status->fraud_status ?? null;
                if (empty($fraudStatus) || $fraudStatus == 'accept') {
                    $userPackage = UserPackage::where('transaction_id', $transaction->id)->first();

                    if (!$userPackage) {
                        $transaction->load('package');
                        $startDate = now();
                        $durationDays = $transaction->package ? $transaction->package->duration_days : 30;
                        $endDate = now()->addDays($durationDays);

                        UserPackage::where('user_id', $transaction->user_id)
                            ->where('is_active', true)
                            ->update(['is_active' => false]);

                        UserPackage::create([
                            'user_id' => $transaction->user_id,
                            'package_id' => $transaction->package_id,
                            'transaction_id' => $transaction->id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'is_active' => true,
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'transaction' => $transaction,
                'midtrans_status' => $status->transaction_status,
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment finish redirect
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        if ($transaction->transaction_status == 'settlement' || $transaction->transaction_status == 'capture') {
            return redirect()->route('user.dashboard')
                ->with('success', 'Pembayaran berhasil! Paket Anda telah diaktifkan.');
        } elseif ($transaction->transaction_status == 'pending') {
            return redirect()->route('user.dashboard')
                ->with('info', 'Pembayaran sedang diproses. Kami akan mengirimkan notifikasi setelah pembayaran berhasil.');
        } else {
            return redirect()->route('user.dashboard')
                ->with('error', 'Pembayaran gagal atau dibatalkan.');
        }
    }

    /**
     * Handle payment unfinish redirect
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('user.dashboard')
            ->with('info', 'Pembayaran belum selesai. Silakan coba lagi jika Anda ingin melanjutkan pembayaran.');
    }

    /**
     * Handle payment error redirect
     */
    public function error(Request $request)
    {
        return redirect()->route('user.dashboard')
            ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
    }
}

