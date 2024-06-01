<?php
    require 'vendor/autoload.php';
    require 'config.php'; 

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    function generate_jwt($user_id) {
        global $jwtSecret;
        $payload = [
            'iss' => 'levart',
            'aud' => 'http://localhost:8000',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + (60 * 60),
            'user_id' => $user_id
        ];

        return JWT::encode($payload, $jwtSecret, 'HS256');
    }

    function validate_jwt($token) {
        global $jwtSecret;
        try {
            $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));
            return (array)$decoded;
        } catch (Exception $e) {
            return false;
        }
    }
?>