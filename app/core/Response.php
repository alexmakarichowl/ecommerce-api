<?php

class Response {
    public static function json($data, int $status = 200) {
        if (headers_sent()) {
            error_log('Headers already sent - cannot send JSON response');
            return;
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            error_log('JSON encoding failed: ' . json_last_error_msg());
            echo json_encode(["error" => "Internal Server Error"], JSON_UNESCAPED_UNICODE);
        } else {
            echo $json;
        }
        
        exit;
    }

    public static function success($data, int $status = 200) {
        self::json(array_merge(['success' => true], (array)$data), $status);
    }

    public static function error($message, int $status = 400) {
        self::json(['success' => false, 'error' => $message], $status);
    }
}
