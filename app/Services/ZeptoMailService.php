<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZeptoMailService
{
    private string $apiKey;

    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.zeptomail.api_key');
        $this->apiUrl = config('services.zeptomail.api_url', 'https://api.zeptomail.com/v1.1/email');
    }

    /**
     * Send email via ZeptoMail API.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function sendEmail(array $data): array
    {
        try {
            Log::info('ZeptoMail Sending Email', [
                'to' => $data['to'] ?? [],
                'subject' => $data['subject'] ?? '',
                'from' => $data['from'] ?? [],
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'accept' => 'application/json',
                    'authorization' => 'Zoho-enczapikey '.$this->apiKey,
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/json',
                ])
                ->post($this->apiUrl, $data);

            if ($response->successful()) {
                Log::info('ZeptoMail API Success', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('ZeptoMail API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);

            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('ZeptoMail Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build email payload for ZeptoMail API.
     *
     * @param  array<int, array<string, mixed>>  $to
     * @param  array<int, array<string, mixed>>  $cc
     * @param  array<int, array<string, mixed>>  $bcc
     * @return array<string, mixed>
     */
    public function buildPayload(
        string $fromAddress,
        ?string $fromName,
        array $to,
        string $subject,
        string $htmlBody,
        ?string $textBody = null,
        array $cc = [],
        array $bcc = []
    ): array {
        $payload = [
            'from' => [
                'address' => $fromAddress,
            ],
            'to' => $to,
            'subject' => $subject,
            'htmlbody' => $htmlBody,
        ];

        if ($fromName) {
            $payload['from']['name'] = $fromName;
        }

        if ($textBody) {
            $payload['textbody'] = $textBody;
        }

        if (! empty($cc)) {
            $payload['cc'] = $cc;
        }

        if (! empty($bcc)) {
            $payload['bcc'] = $bcc;
        }

        return $payload;
    }
}
