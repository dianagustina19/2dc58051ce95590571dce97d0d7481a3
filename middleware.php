<?php
    require 'jwt.php';

    function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_BEARER'])) {
            $headers = trim($_SERVER["HTTP_BEARER"]);
        }
        return $headers;
    }

    function getBearerToken() {
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    function authenticate() {
        $token = getBearerToken();
        if (!$token) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $valid = validate_jwt($token);
        if (isset($valid['error'])) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['error' => 'Unauthorized: ' . $valid['error']]);
            exit;
        }
    }
?>
