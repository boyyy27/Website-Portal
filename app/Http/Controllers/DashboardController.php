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

        // Get daily sales (last 7 days)
        $dailySales = Transaction::settled()
            ->select(DB::raw("DATE(created_at) as date"), DB::raw('sum(gross_amount) as revenue'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get package sales data
        $packageSales = Transaction::settled()
            ->join('packages', 'transactions.package_id', '=', 'packages.id')
            ->select('packages.name', DB::raw('sum(transactions.gross_amount) as revenue'), DB::raw('count(*) as count'))
            ->groupBy('packages.id', 'packages.name')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalTransactions',
            'pendingTransactions',
            'settledTransactions',
            'totalRevenue',
            'recentTransactions',
            'transactionsByStatus',
            'packages',
            'monthlyRevenue',
            'dailySales',
            'packageSales'
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
            ->get();

        // Statistics for transactions page
        $totalTransactions = Transaction::count();
        $pendingTransactions = Transaction::pending()->count();
        $settledTransactions = Transaction::settled()->count();
        $totalRevenue = Transaction::settled()->sum('gross_amount');

        return view('dashboard.transactions', compact(
            'transactions',
            'totalTransactions',
            'pendingTransactions',
            'settledTransactions',
            'totalRevenue'
        ));
    }

    /**
     * Delete a transaction
     */
    public function deleteTransaction($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $transaction = Transaction::findOrFail($id);
        
        // Check if transaction is settled - might want to prevent deletion
        if ($transaction->transaction_status === 'settlement') {
            return redirect()->route('admin.transactions')
                ->with('error', 'Transaksi yang sudah settlement tidak dapat dihapus');
        }

        $transaction->delete();

        return redirect()->route('admin.transactions')
            ->with('success', 'Transaksi berhasil dihapus');
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

        // Get user transaction history for chart (last 6 months)
        $userMonthlyTransactions = Transaction::where('user_id', $user->id)
            ->select(DB::raw("DATE_TRUNC('month', created_at) as month"), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Get user transaction status breakdown
        $userTransactionStatus = Transaction::where('user_id', $user->id)
            ->select('transaction_status', DB::raw('count(*) as total'))
            ->groupBy('transaction_status')
            ->get();

        // Statistics for user dashboard
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $pendingTransactions = Transaction::where('user_id', $user->id)->where('transaction_status', 'pending')->count();
        $settledTransactions = Transaction::where('user_id', $user->id)->where('transaction_status', 'settlement')->count();
        $totalSpent = Transaction::where('user_id', $user->id)->where('transaction_status', 'settlement')->sum('gross_amount');

        return view('dashboard.user', compact(
            'activeSubscription',
            'subscriptions',
            'transactions',
            'invoices',
            'userMonthlyTransactions',
            'userTransactionStatus',
            'totalTransactions',
            'pendingTransactions',
            'settledTransactions',
            'totalSpent'
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

