<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationEmail;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and email is verified
        if ($user && !$user->email_verified) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi. Silakan verifikasi email Anda terlebih dahulu.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate 6 digit verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $verificationToken = Str::random(60);
        $expiresAt = now()->addMinutes(15);

        try {
            // Prepare user data - only include fields that exist
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ];

            // Add optional fields if they exist in database
            try {
                if (Schema::hasColumn('users', 'role')) {
                    $userData['role'] = 'user';
                }
                if (Schema::hasColumn('users', 'is_active')) {
                    $userData['is_active'] = true;
                }
                if (Schema::hasColumn('users', 'email_verified')) {
                    $userData['email_verified'] = false;
                }
                if (Schema::hasColumn('users', 'verification_token')) {
                    $userData['verification_token'] = $verificationToken;
                }
                if (Schema::hasColumn('users', 'verification_token_expires')) {
                    $userData['verification_token_expires'] = $expiresAt;
                }
            } catch (\Exception $schemaError) {
                \Log::warning('Schema check error (non-fatal): ' . $schemaError->getMessage());
                // Continue without optional fields - will use defaults if migration not run yet
            }

            $user = User::create($userData);

            // Store verification code in session temporarily
            session(['verification_code_' . $user->id => $verificationCode]);
            session(['verification_user_id' => $user->id]);

            // Kirim email verifikasi menggunakan Queue (background job)
            // Ini akan mencegah timeout karena email dikirim di background
            $emailSent = false;
            try {
                // Check if mail is configured
                $mailHost = config('mail.mailers.smtp.host');
                $mailUsername = config('mail.mailers.smtp.username');
                $mailPassword = config('mail.mailers.smtp.password');

                if (!empty($mailHost) && !empty($mailUsername) && !empty($mailPassword)) {
                    // Dispatch email job ke queue (akan diproses di background)
                    SendVerificationEmail::dispatch($user->email, $verificationCode, $user->name, $user->id);
                    
                    \Log::info('Verification email job dispatched to queue for: ' . $user->email);
                    $emailSent = true;
                    session(['email_sent_' . $user->id => true]);
                } else {
                    \Log::warning('Email not configured properly.');
                }
            } catch (\Exception $e) {
                \Log::warning('Email dispatch error: ' . $e->getMessage());
            }
            
            // Redirect to verification page
            return redirect()->route('verification.show')
                ->with('success', 'Registrasi berhasil! Kode verifikasi telah dikirim ke email Anda. Silakan cek folder Inbox dan Spam.');

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Registration database error: ' . $e->getMessage());
            
            // Check if error is due to missing columns
            if (str_contains($e->getMessage(), 'column') && str_contains($e->getMessage(), 'does not exist')) {
                return back()->withErrors([
                    'email' => 'Database schema belum lengkap. Silakan jalankan migrations terlebih dahulu.',
                ])->withInput();
            }

            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi atau hubungi administrator.',
            ])->withInput();

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
            ])->withInput();
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Logout berhasil!');
    }
}

