<?php

class User {

    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function getIdByEmail(string $email): ?int {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? (int) $row['id'] : null;
    }

    public function register($email, $password) {
        if (empty($email) || empty($password)) {
            throw new InvalidArgumentException('Email and password are required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException('Password must be at least 6 characters');
        }

        if ($this->emailExists($email)) {
            throw new Exception('Email already exists');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO users (email, password) VALUES (?, ?)"
        );

        $stmt->execute([
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        ]);

        return $this->db->lastInsertId();
    }

    public function verify($email, $password) {
        if (empty($email) || empty($password)) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (!$user) return false;

        return password_verify($password, $user['password']);
    }

    public function saveToken($email, $token) {
        $stmt = $this->db->prepare(
            "UPDATE users SET api_token = ? WHERE email = ?"
        );
        $stmt->execute([$token, $email]);
    }

    public function checkToken($token): bool {
        if (empty($token)) return false;
        $stmt = $this->db->prepare(
            "SELECT id FROM users WHERE api_token = ?"
        );
        $stmt->execute([$token]);
        return (bool) $stmt->fetch();
    }
}
