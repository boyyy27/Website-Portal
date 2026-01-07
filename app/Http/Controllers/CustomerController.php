<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers (for admin)
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $customers = User::where('role', '!=', 'admin')
            ->orWhereNull('role')
            ->withCount(['transactions', 'userPackages'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistics for customers page
        $totalCustomers = User::where(function($query) {
            $query->where('role', '!=', 'admin')->orWhereNull('role');
        })->count();
        $activeCustomers = User::where(function($query) {
            $query->where('role', '!=', 'admin')->orWhereNull('role');
        })
            ->whereHas('userPackages', function($query) {
                $query->where('is_active', true)
                      ->where('end_date', '>', now());
            })
            ->count();
        $customersWithTransactions = User::where(function($query) {
            $query->where('role', '!=', 'admin')->orWhereNull('role');
        })
            ->whereHas('transactions')
            ->count();
        $totalTransactions = \App\Models\Transaction::whereHas('user', function($query) {
            $query->where(function($q) {
                $q->where('role', '!=', 'admin')->orWhereNull('role');
            });
        })->count();
        
        return view('dashboard.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'customersWithTransactions',
            'totalTransactions'
        ));
    }
}
