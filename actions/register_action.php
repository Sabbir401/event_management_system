<?php
session_start();
require '../includes/db.php';
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation
    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[\W]/', $password) || 
        !preg_match('/\d/', $password)) {
        
        $response['status'] = 'error';
        $response['message'] = "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
        echo json_encode($response);
        exit;
    }

    // Confirm password validation
    if ($password !== $confirm_password) {
        $response['status'] = 'error';
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $response['status'] = 'error';
            $response['message'] = "Email is already registered.";
            echo json_encode($response);
            exit;
        }

        // Insert user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

        $response['status'] = 'success';
        $response['message'] = "Registration successful! You can now log in.";
        echo json_encode($response);
        exit;

    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = "Registration failed: " . $e->getMessage();
        echo json_encode($response);
        exit;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "Invalid request.";
    echo json_encode($response);
    exit;
}
?>
