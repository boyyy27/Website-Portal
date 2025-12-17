<?php

namespace App\Jobs;

use App\Mail\VerificationCodeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $verificationCode;
    protected $userName;
    protected $userId;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $verificationCode, $userName, $userId)
    {
        $this->email = $email;
        $this->verificationCode = $verificationCode;
        $this->userName = $userName;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Sending verification email to: ' . $this->email . ' (User ID: ' . $this->userId . ')');
            
            Mail::to($this->email)->send(new VerificationCodeMail($this->verificationCode, $this->userName));
            
            // Mark email as sent in session (untuk informasi user)
            session(['email_sent_' . $this->userId => true]);
            
            Log::info('Verification email sent successfully to: ' . $this->email);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email to: ' . $this->email . ' - Error: ' . $e->getMessage());
            
            // Rethrow exception agar job masuk ke failed_jobs (akan di-retry otomatis)
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Verification email job failed permanently for: ' . $this->email . ' - Error: ' . $exception->getMessage());
    }
}

