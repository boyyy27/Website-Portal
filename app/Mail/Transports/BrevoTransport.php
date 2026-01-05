<?php

namespace App\Mail\Transports;

use Illuminate\Mail\Transport\Transport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Message;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport extends Transport
{
    protected $apiKey;
    protected $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();
        
        // Prepare recipients
        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: '',
            ];
        }

        // Prepare CC (if any)
        $cc = [];
        foreach ($email->getCc() as $address) {
            $cc[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: '',
            ];
        }

        // Prepare BCC (if any)
        $bcc = [];
        foreach ($email->getBcc() as $address) {
            $bcc[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: '',
            ];
        }

        // Prepare payload
        $payload = [
            'sender' => [
                'email' => $email->getFrom()[0]->getAddress(),
                'name' => $email->getFrom()[0]->getName() ?: '',
            ],
            'to' => $to,
            'subject' => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody(),
            'textContent' => $email->getTextBody(),
        ];

        // Add CC if exists
        if (!empty($cc)) {
            $payload['cc'] = $cc;
        }

        // Add BCC if exists
        if (!empty($bcc)) {
            $payload['bcc'] = $bcc;
        }

        // Add reply-to if exists
        if (count($email->getReplyTo()) > 0) {
            $payload['replyTo'] = [
                'email' => $email->getReplyTo()[0]->getAddress(),
                'name' => $email->getReplyTo()[0]->getName() ?: '',
            ];
        }

        // Send via Brevo API
        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if (!$response->successful()) {
                Log::error('Brevo API Error: ' . $response->body());
                throw new \Exception('Failed to send email via Brevo API: ' . $response->body());
            }

            Log::info('Email sent via Brevo API: ' . json_encode($response->json()));
        } catch (\Exception $e) {
            Log::error('Brevo API Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
