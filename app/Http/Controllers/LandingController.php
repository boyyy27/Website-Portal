<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // Get active packages from database
        $packages = Package::active()
            ->orderBy('price', 'asc')
            ->get();

        // Check if authenticated user has active subscription
        $hasActiveSubscription = false;
        if (Auth::check()) {
            $hasActiveSubscription = UserPackage::where('user_id', Auth::id())
                ->where('is_active', true)
                ->where('end_date', '>', now())
                ->exists();
        }

        return view('landing', compact('packages', 'hasActiveSubscription'));
    }
}

