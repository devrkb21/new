<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $senderId;
    protected $url;

    public function __construct()
    {
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.senderid');
        $this->url = config('services.sms.url');
    }

    /**
     * Send an SMS message.
     *
     * @param string $number The recipient's phone number.
     * @param string $message The message content.
     * @return bool True on success, false on failure.
     */
    public function send(string $number, string $message): bool
    {
        if (!$this->apiKey || !$this->senderId || !$this->url) {
            Log::error('SMS service is not configured. Please check your .env file.');
            return false;
        }

        try {
            $response = Http::asForm()->post($this->url, [
                'api_key' => $this->apiKey,
                'senderid' => $this->senderId,
                'number' => $number,
                'message' => $message,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['response_code']) && $responseData['response_code'] == 202) {
                Log::info('SMS sent successfully to ' . $number);
                return true;
            } else {
                $errorMessage = $responseData['error_message'] ?? 'Unknown error';
                Log::error('Failed to send SMS to ' . $number . '. Response: ' . $errorMessage);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception caught while sending SMS: ' . $e->getMessage());
            return false;
        }
    }
}