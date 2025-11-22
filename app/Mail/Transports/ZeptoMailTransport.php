<?php

namespace App\Mail\Transports;

use App\Services\ZeptoMailService;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class ZeptoMailTransport extends AbstractTransport
{
    public function __construct(private ZeptoMailService $zeptoMailService)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom()[0] ?? null;
        $fromAddress = $from?->getAddress() ?? config('mail.from.address');
        $fromName = $from?->getName() ?? config('mail.from.name');

        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email_address' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName(),
                ],
            ];
        }

        $cc = [];
        foreach ($email->getCc() as $address) {
            $cc[] = [
                'email_address' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName(),
                ],
            ];
        }

        $bcc = [];
        foreach ($email->getBcc() as $address) {
            $bcc[] = [
                'email_address' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName(),
                ],
            ];
        }

        $subject = $email->getSubject() ?? '';

        $htmlBody = '';
        $textBody = null;

        if ($email->getHtmlBody()) {
            $htmlBody = $email->getHtmlBody();
        }

        if ($email->getTextBody()) {
            $textBody = $email->getTextBody();
        }

        $payload = $this->zeptoMailService->buildPayload(
            fromAddress: $fromAddress,
            fromName: $fromName,
            to: $to,
            subject: $subject,
            htmlBody: $htmlBody,
            textBody: $textBody,
            cc: $cc,
            bcc: $bcc
        );

        $result = $this->zeptoMailService->sendEmail($payload);

        if (! $result['success']) {
            \Log::error('ZeptoMail Transport Error', [
                'error' => $result['error'] ?? 'Unknown error',
                'status' => $result['status'] ?? null,
                'to' => $to,
                'subject' => $subject,
            ]);
            throw new \RuntimeException('ZeptoMail API Error: '.($result['error'] ?? 'Unknown error'));
        }

        \Log::info('ZeptoMail Email Sent Successfully', [
            'to' => array_map(fn ($t) => $t['email_address']['address'], $to),
            'subject' => $subject,
            'response' => $result['data'] ?? null,
        ]);
    }

    public function __toString(): string
    {
        return 'zeptomail';
    }
}
