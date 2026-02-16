<?php

class JwtService
{
    private static function getSecret(): string
    {
        $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-in-production';
        if (strlen($secret) < 32) {
            error_log('JWT_SECRET should be at least 32 characters for security');
        }
        return $secret;
    }

    public static function encode(array $payload, int $expireHours = 24): string
    {
        $now = time();
        $payload = array_merge($payload, [
            'iat' => $now,
            'exp' => $now + ($expireHours * 3600),
        ]);

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $headerEncoded = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));
        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::getSecret(),
            true
        );
        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    public static function decode(string $token): ?object
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::getSecret(),
            true
        );
        $expectedSignature = self::base64UrlEncode($signature);

        if (!hash_equals($expectedSignature, $signatureEncoded)) {
            return null;
        }

        $payloadJson = base64_decode(strtr($payloadEncoded, '-_', '+/'), true);
        if ($payloadJson === false) {
            return null;
        }

        $payload = json_decode($payloadJson);
        if ($payload === null || !isset($payload->exp)) {
            return null;
        }

        if ($payload->exp < time()) {
            return null; // Expired
        }

        return $payload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
