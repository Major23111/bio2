<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

if (! function_exists('encrypt_url_value')) {
    function encrypt_url_value(null|int|string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return rtrim(strtr(Crypt::encryptString((string) $value), '+/', '-_'), '=');
    }
}

if (! function_exists('decrypt_url_value')) {
    function decrypt_url_value(null|int|string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            return $value;
        }

        $decodedValue = strtr($value, '-_', '+/');
        $paddingLength = strlen($decodedValue) % 4;

        if ($paddingLength !== 0) {
            $decodedValue .= str_repeat('=', 4 - $paddingLength);
        }

        try {
            return Crypt::decryptString($decodedValue);
        } catch (DecryptException) {
            throw new NotFoundHttpException();
        }
    }
}
