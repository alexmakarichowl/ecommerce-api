<?php

class AuthMiddleware {

    public static function handle(): ?object {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } elseif (!empty($_SERVER)) {
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                    $headers[$name] = $value;
                }
            }
        }

        $authHeader = null;
        foreach ($headers as $k => $v) {
            if (strcasecmp($k, 'Authorization') === 0) {
                $authHeader = $v;
                break;
            }
        }

        if (empty($authHeader)) {
            Response::json(["error" => "Unauthorized - missing token"], 401);
        }

        $token = trim(preg_replace('/^Bearer\s+/i', '', $authHeader));

        if (empty($token)) {
            Response::json(["error" => "Unauthorized - empty token"], 401);
        }

        $payload = JwtService::decode($token);

        if ($payload === null) {
            Response::json(["error" => "Unauthorized - invalid or expired token"], 401);
        }

        return $payload;
    }
}
