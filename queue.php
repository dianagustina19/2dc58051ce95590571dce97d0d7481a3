<?php
require 'database.php';
require 'send-email.php';
$pdo = include 'database.php';

while (true) {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM emails WHERE status = 'false'");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($count > 0) {
        $stmt = $pdo->query("SELECT * FROM emails LIMIT 1");
        $email = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($email) {
            $success = send_email($email['receipt'], $email['subject'], $email['body']);
            if ($success) {
                $stmt = $pdo->prepare("DELETE FROM emails WHERE id = :id");
                $stmt->execute(['id' => $email['id']]);
            }
        }
    }

    sleep(10);
}
?>
