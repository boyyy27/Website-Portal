<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

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

        return view('landing', compact('packages'));
    }
}

