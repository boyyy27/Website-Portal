<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TmsController extends Controller
{
    /**
     * Redirect to TMS with auto-login attempt
     */
    public function redirectToTms()
    {
        $user = Auth::user();
        
        // Check if user has active subscription
        $hasActiveSubscription = \App\Models\UserPackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->exists();
        
        if (!$hasActiveSubscription) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda harus memiliki langganan aktif untuk mengakses TMS.');
        }
        
        // TMS credentials (bisa diubah ke config atau database)
        $tmsUrl = 'https://tms.omile.id/demo/dashboard';
        $tmsUsername = 'DEVODI';
        $tmsPassword = 'XRandom20';
        
        return view('tms.redirect', compact('tmsUrl', 'tmsUsername', 'tmsPassword'));
    }
}

