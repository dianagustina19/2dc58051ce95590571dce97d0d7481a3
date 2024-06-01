<?php
    require 'jwt.php';

    function getAuthorizationHeader() {
        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        } else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
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
        $jwt = getBearerToken();
        if ($jwt) {
            $payload = validate_jwt($jwt);
            if ($payload) {
                return $payload;
            }
        }
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
?>
