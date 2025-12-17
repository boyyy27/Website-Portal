<?php

namespace App\Mail;

use Illuminate\Mail\Transport\Transport;
use Resend;
use Swift_Mime_SimpleMessage;

class ResendTransport extends Transport
{
    protected $client;

    public function __construct()
    {
        $apiKey = config('services.resend.key');
        $this->client = Resend::client($apiKey);
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $to = collect($this->getTo($message))->map(function ($email, $name) {
            return $name ? "{$name} <{$email}>" : $email;
        })->values()->toArray();

        $from = $this->getFrom($message);

        try {
            $response = $this->client->emails->send([
                'from' => $from,
                'to' => $to,
                'subject' => $message->getSubject(),
                'html' => $message->getBody(),
            ]);

            $this->sendPerformed($message);

            return $this->numberOfRecipients($message);
        } catch (\Exception $e) {
            \Log::error('Resend API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getTo(Swift_Mime_SimpleMessage $message)
    {
        return $message->getTo();
    }

    protected function getFrom(Swift_Mime_SimpleMessage $message)
    {
        $from = $message->getFrom();
        $fromEmail = array_keys($from)[0];
        $fromName = array_values($from)[0];

        return $fromName ? "{$fromName} <{$fromEmail}>" : $fromEmail;
    }
}

