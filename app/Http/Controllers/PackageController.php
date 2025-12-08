<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of packages (for admin)
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $packages = Package::orderBy('created_at', 'desc')->get();
        return view('dashboard.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        return view('dashboard.packages.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable',
            'features' => 'nullable|array',
            'features.*.text' => 'required_with:features|string',
            'features.*.included' => 'nullable',
        ]);

        // Parse features from request
        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $index => $feature) {
                // Only add feature if text is not empty
                if (isset($feature['text']) && !empty(trim($feature['text']))) {
                    // Check if included checkbox is checked
                    // Hidden input mengirim value '0', checkbox mengirim value '1' jika dicentang
                    // Laravel akan mengambil value terakhir jika ada duplikat name
                    $includedValue = $request->input("features.{$index}.included", '0');
                    
                    // Convert to boolean - check if value is '1' or 1
                    $included = ($includedValue === '1' || $includedValue === 1 || $includedValue === true);
                    
                    $features[] = [
                        'text' => trim($feature['text']),
                        'included' => (bool) $included,
                    ];
                }
            }
        }

        Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'is_active' => $request->has('is_active') ? true : false,
            'features' => !empty($features) ? $features : null,
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $package = Package::findOrFail($id);
        return view('dashboard.packages.edit', compact('package'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable',
            'features' => 'nullable|array',
        ]);
        
        // Log untuk debug
        Log::info('Package update request', [
            'package_id' => $id,
            'validated_data' => $validated,
            'request_all' => $request->all()
        ]);

        // Parse features from request
        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $index => $feature) {
                if (!empty($feature['text'])) {
                    // Check if included checkbox is checked
                    // Hidden input mengirim value '0', checkbox mengirim value '1' jika dicentang
                    // Laravel akan mengambil value terakhir jika ada duplikat name
                    $includedValue = $request->input("features.{$index}.included", '0');
                    
                    // Convert to boolean - check if value is '1' or 1
                    $included = ($includedValue === '1' || $includedValue === 1 || $includedValue === true);
                    
                    $features[] = [
                        'text' => trim($feature['text']),
                        'included' => (bool) $included,
                    ];
                }
            }
        }

        $package = Package::findOrFail($id);
        
        // Store old values for logging
        $oldValues = [
            'name' => $package->name,
            'price' => $package->price,
            'duration_days' => $package->duration_days,
        ];
        
        // Update package directly - assign each field
        $package->name = trim($validated['name']);
        $package->description = !empty($validated['description']) ? trim($validated['description']) : null;
        $package->price = (float) $validated['price'];
        $package->duration_days = (int) $validated['duration_days'];
        $package->is_active = $request->has('is_active') ? true : false;
        $package->features = !empty($features) ? $features : null;
        
        // Save the package
        $saved = $package->save();
        
        // Refresh to get updated values
        $package->refresh();

        // Log the update
        Log::info('Package updated', [
            'package_id' => $id,
            'old_values' => $oldValues,
            'new_values' => [
                'name' => $package->name,
                'price' => $package->price,
                'duration_days' => $package->duration_days,
            ],
            'saved' => $saved
        ]);

        if (!$saved) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui paket. Silakan coba lagi.');
        }

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil diperbarui');
    }

    /**
     * Remove the specified package
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak');
        }

        $package = Package::findOrFail($id);
        
        // Check if package has active transactions
        if ($package->transactions()->count() > 0) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Paket tidak dapat dihapus karena memiliki transaksi');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus');
    }
}

