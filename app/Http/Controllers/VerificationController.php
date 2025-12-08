<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerificationController extends Controller
{
    /**
     * Show verification form
     */
    public function show()
    {
        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'User tidak ditemukan.');
        }

        if ($user->email_verified) {
            return redirect()->route('login')
                ->with('success', 'Email sudah diverifikasi. Silakan login.');
        }

        return view('auth.verify', compact('user'));
    }

    /**
     * Verify email with code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ], [
            'verification_code.required' => 'Kode verifikasi harus diisi.',
            'verification_code.size' => 'Kode verifikasi harus 6 digit.',
        ]);

        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'User tidak ditemukan.');
        }

        // Check if code matches
        $storedCode = session('verification_code_' . $user->id);
        
        if ($storedCode !== $request->verification_code) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi tidak valid.',
            ])->withInput();
        }

        // Check if code expired
        if ($user->verification_token_expires && $user->verification_token_expires < now()) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi sudah kadaluarsa. Silakan minta kode baru.',
            ])->withInput();
        }

        // Verify user
        $user->update([
            'email_verified' => true,
            'verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires' => null,
        ]);

        // Clear session
        session()->forget('verification_code_' . $user->id);
        session()->forget('verification_user_id');

        // Auto login
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang di OMILE.');
    }

    /**
     * Resend verification code
     */
    public function resend()
    {
        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'User tidak ditemukan.');
        }

        if ($user->email_verified) {
            return redirect()->route('login')
                ->with('success', 'Email sudah diverifikasi. Silakan login.');
        }

        // Generate new verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        $user->update([
            'verification_token_expires' => $expiresAt,
        ]);

        // Store new code in session
        session(['verification_code_' . $user->id => $verificationCode]);

        // Send verification email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $user->name));
            
            return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }
}

