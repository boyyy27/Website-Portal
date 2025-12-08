<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        // Get transaction statistics
        $totalTransactions = Transaction::count();
        $pendingTransactions = Transaction::pending()->count();
        $settledTransactions = Transaction::settled()->count();
        $totalRevenue = Transaction::settled()->sum('gross_amount');

        // Get recent transactions
        $recentTransactions = Transaction::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get transactions by status
        $transactionsByStatus = Transaction::select('transaction_status', DB::raw('count(*) as total'))
            ->groupBy('transaction_status')
            ->get();

        // Get all packages
        $packages = Package::orderBy('created_at', 'desc')->get();

        // Get monthly revenue (last 6 months)
        $monthlyRevenue = Transaction::settled()
            ->select(DB::raw("DATE_TRUNC('month', created_at) as month"), DB::raw('sum(gross_amount) as revenue'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('dashboard.admin', compact(
            'totalTransactions',
            'pendingTransactions',
            'settledTransactions',
            'totalRevenue',
            'recentTransactions',
            'transactionsByStatus',
            'packages',
            'monthlyRevenue'
        ));
    }

    /**
     * Show all transactions for admin
     */
    public function allTransactions()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $transactions = Transaction::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.transactions', compact('transactions'));
    }

    /**
     * Show user dashboard
     */
    public function userDashboard()
    {
        $user = Auth::user();

        // Get active subscription
        $activeSubscription = UserPackage::with(['package', 'transaction'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->first();

        // Get all user subscriptions (active and expired)
        $subscriptions = UserPackage::with(['package', 'transaction'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user transactions
        $transactions = Transaction::with('package')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get invoices (settled transactions)
        $invoices = Transaction::with('package')
            ->where('user_id', $user->id)
            ->where('transaction_status', 'settlement')
            ->orderBy('settlement_time', 'desc')
            ->get();

        return view('dashboard.user', compact(
            'activeSubscription',
            'subscriptions',
            'transactions',
            'invoices'
        ));
    }

    /**
     * Show transaction detail
     */
    public function showTransaction($id)
    {
        $user = Auth::user();
        $transaction = Transaction::with(['user', 'package', 'paymentLogs'])
            ->findOrFail($id);

        // Check if user has access to this transaction
        if (!$user->isAdmin() && $transaction->user_id !== $user->id) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        return view('dashboard.transaction-detail', compact('transaction'));
    }

    /**
     * Download invoice
     */
    public function downloadInvoice($id)
    {
        $user = Auth::user();
        $transaction = Transaction::with(['user', 'package'])
            ->findOrFail($id);

        // Check if user has access to this transaction
        if (!$user->isAdmin() && $transaction->user_id !== $user->id) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        // Check if transaction is settled
        if ($transaction->transaction_status !== 'settlement') {
            return redirect()->back()->with('error', 'Invoice hanya tersedia untuk transaksi yang sudah dibayar');
        }

        return view('dashboard.invoice', compact('transaction'));
    }
}

