<?php
    require 'vendor/autoload.php';
    require 'config.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\SignatureInvalidException;
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
        } catch (ExpiredException $e) {
            return ['error' => 'Token has expired'];
        } catch (SignatureInvalidException $e) {
            return ['error' => 'Invalid token signature'];
        } catch (Exception $e) {
            return ['error' => 'Invalid token'];
        }
    }
?>