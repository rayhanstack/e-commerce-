<?php

namespace Modules\Core\Contracts;

interface SmsProviderInterface
{
    /**
     * Send an SMS message to a phone number.
     *
     * @param  string  $to  Phone number with country code.
     * @param  string  $message  The message body.
     * @return bool True on success, false on failure.
     */
    public function send(string $to, string $message): bool;

    /**
     * Get the current balance of the SMS provider account.
     */
    public function getBalance(): ?float;
}
