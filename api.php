<?php
require 'vendor/autoload.php';
require 'middleware.php';

$pdo = include 'database.php';

header('Content-Type: application/json');

// authenticate();

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['receipt']) || !isset($data['subject']) || !isset($data['body'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO emails (receipt, subject, body, status) VALUES (:receipt, :subject, :body, 'false')");
    $stmt->execute([
        'receipt' => $data['receipt'],
        'subject' => $data['subject'],
        'body' => $data['body']
    ]);

    echo json_encode(['message' => 'Email queued for sending']);
    exit;
} else {
    echo json_encode(['error' => 'Method not allowed']);
    http_response_code(405); // Method not allowed
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found']);
exit;
?>
