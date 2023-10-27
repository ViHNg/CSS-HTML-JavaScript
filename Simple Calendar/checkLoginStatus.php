<?php
ini_set("session.cookie_httponly", 1);
session_start();

$response = ['loggedIn' => false];

if (isset($_SESSION['username'])) {
    $response['loggedIn'] = true;
    $response['username'] = $_SESSION['username'];
} elseif (isset($_COOKIE['user_login'])) {
    require 'database.php'; // Assuming you've established a connection in this file

    $tokenFromCookie = $_COOKIE['user_login'];

    $stmt = $mysqli->prepare("SELECT user_id FROM user_tokens WHERE token_value = ? AND expiry_date > NOW()");
    if ($stmt) {
        $stmt->bind_param('s', $tokenFromCookie);
        $stmt->execute();
        $stmt->bind_result($userIDFromToken);
        
        if ($stmt->fetch()) {
            // Get additional user details from the Users table (like username) if needed
            $stmtUser = $mysqli->prepare("SELECT username FROM Users WHERE id = ?");
            $stmtUser->bind_param('i', $userIDFromToken);
            $stmtUser->execute();
            $stmtUser->bind_result($usernameFromToken);
            
            if ($stmtUser->fetch()) {
                $_SESSION['username'] = $usernameFromToken;
                $_SESSION['user_id'] = $userIDFromToken;
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

                $response['loggedIn'] = true;
                $response['username'] = $usernameFromToken;
            }
            $stmtUser->close();
        }
        $stmt->close();
    }
}

echo json_encode($response);
?>
