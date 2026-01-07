<?php

namespace App\Mail\Transports;

use Swift_Transport;
use Swift_Mime_SimpleMessage;
use Swift_Events_EventListener;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport implements Swift_Transport
{
    protected $apiKey;
    protected $apiUrl = 'https://api.brevo.com/v3/smtp/email';
    protected $started = false;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Test if this Transport mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Start this Transport mechanism.
     */
    public function start()
    {
        $this->started = true;
    }

    /**
     * Stop this Transport mechanism.
     */
    public function stop()
    {
        $this->started = false;
    }

    /**
     * Check if this Transport mechanism is alive.
     *
     * @return bool
     */
    public function ping()
    {
        return true;
    }

    /**
     * Send the given Message.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @param string[] $failedRecipients An array of failures by-reference
     * @return int
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->start();

        $failedRecipients = (array) $failedRecipients;

        // Get recipients
        $to = [];
        foreach ((array) $message->getTo() as $email => $name) {
            $to[] = [
                'email' => $email,
                'name' => $name ?: $email, // Brevo requires name field, use email as fallback
            ];
        }

        // Get CC recipients
        $cc = [];
        foreach ((array) $message->getCc() as $email => $name) {
            $cc[] = [
                'email' => $email,
                'name' => $name ?: $email, // Brevo requires name field, use email as fallback
            ];
        }

        // Get BCC recipients
        $bcc = [];
        foreach ((array) $message->getBcc() as $email => $name) {
            $bcc[] = [
                'email' => $email,
                'name' => $name ?: $email, // Brevo requires name field, use email as fallback
            ];
        }

        // Get sender
        $from = $message->getFrom();
        $fromEmail = key($from);
        $fromName = $from[$fromEmail] ?? '';

        $sender = [
            'email' => $fromEmail,
            'name' => $fromName ?: $fromEmail, // Brevo requires name field, use email as fallback
        ];

        // Get reply-to
        $replyTo = [];
        if ($message->getReplyTo()) {
            foreach ((array) $message->getReplyTo() as $email => $name) {
                $replyTo = [
                    'email' => $email,
                    'name' => $name ?: $email, // Brevo requires name field, use email as fallback
                ];
                break; // Brevo only supports one reply-to
            }
        }

        // Prepare payload
        $payload = [
            'sender' => $sender,
            'to' => $to,
            'subject' => $message->getSubject(),
        ];

        // Add HTML content
        $body = $message->getBody();
        if ($body) {
            $payload['htmlContent'] = $body;
        }

        // Try to get text version if available
        $children = $message->getChildren();
        foreach ($children as $child) {
            if ($child->getContentType() === 'text/plain') {
                $payload['textContent'] = $child->getBody();
                break;
            }
        }

        // Add CC if exists
        if (!empty($cc)) {
            $payload['cc'] = $cc;
        }

        // Add BCC if exists
        if (!empty($bcc)) {
            $payload['bcc'] = $bcc;
        }

        // Add reply-to if exists
        if (!empty($replyTo)) {
            $payload['replyTo'] = $replyTo;
        }

        // Send via Brevo API
        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Brevo API Error: ' . $errorBody);
                
                // Mark all recipients as failed
                foreach ($to as $recipient) {
                    $failedRecipients[] = $recipient['email'];
                }
                foreach ($cc as $recipient) {
                    $failedRecipients[] = $recipient['email'];
                }
                foreach ($bcc as $recipient) {
                    $failedRecipients[] = $recipient['email'];
                }
                
                throw new \Exception('Failed to send email via Brevo API: ' . $errorBody);
            }

            $result = $response->json();
            Log::info('Email sent via Brevo API: ' . json_encode($result));

            // Return number of successful recipients
            return count($to) + count($cc) + count($bcc);
        } catch (\Exception $e) {
            Log::error('Brevo API Exception: ' . $e->getMessage());
            
            // Mark all recipients as failed
            foreach ($to as $recipient) {
                $failedRecipients[] = $recipient['email'];
            }
            foreach ($cc as $recipient) {
                $failedRecipients[] = $recipient['email'];
            }
            foreach ($bcc as $recipient) {
                $failedRecipients[] = $recipient['email'];
            }
            
            throw $e;
        }
    }

    /**
     * Register a plugin in the Transport.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        // Not needed for Brevo API
    }
}

