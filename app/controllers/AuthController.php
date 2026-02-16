<?php

class AuthController {

    public function register() {

        $data = json_decode(file_get_contents("php://input"), true);

        if ($data === null) {
            Response::json(["error" => "Invalid JSON"], 400);
            return;
        }

        if (empty($data['email']) || empty($data['password'])) {
            Response::json(["error" => "Email and password are required"], 400);
            return;
        }

        if (strlen($data['password']) < 6) {
            Response::json(["error" => "Password must be at least 6 characters"], 400);
            return;
        }

        $user = new User();
        try {
            $user->register($data['email'], $data['password']);
            Response::json(["message" => "User registered"], 201);
        } catch (Exception $e) {
            Response::json(["error" => $e->getMessage()], 400);
        }
    }

    public function login() {

        $data = json_decode(file_get_contents("php://input"), true);

        if ($data === null) {
            Response::json(["error" => "Invalid JSON"], 400);
            return;
        }

        if (empty($data['email']) || empty($data['password'])) {
            Response::json(["error" => "Email and password are required"], 400);
            return;
        }

        $user = new User();

        if (!$user->verify($data['email'], $data['password'])) {
            Response::json(["error" => "Invalid credentials"], 401);
            return;
        }

        $userId = $user->getIdByEmail($data['email']);
        $token = JwtService::encode(['user_id' => $userId, 'email' => $data['email']]);

        Response::json(["token" => $token]);
    }
}
