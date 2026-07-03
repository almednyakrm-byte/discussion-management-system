<?php
// Start the session to store user data
session_start();

// Include database connection file
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return JSON response with user data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if user is trying to register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if input fields are not empty
    if (empty($username) || empty($email) || empty($password)) {
        $response = array(
            'status' => 'error',
            'message' => 'Please fill in all fields.'
        );
        echo json_encode($response);
        exit;
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid email address.'
        );
        echo json_encode($response);
        exit;
    }

    // Check if username is unique
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->fetch()) {
        $response = array(
            'status' => 'error',
            'message' => 'Username already taken.'
        );
        echo json_encode($response);
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password_hash);
    $stmt->execute();

    // Get user ID
    $user_id = $db->lastInsertId();

    // Store user data in session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;

    // Return JSON response with user data
    $response = array(
        'status' => 'registered',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if user is trying to login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if input fields are not empty
    if (empty($username) || empty($password)) {
        $response = array(
            'status' => 'error',
            'message' => 'Please fill in all fields.'
        );
        echo json_encode($response);
        exit;
    }

    // Check if username exists in database
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        // Return JSON response with user data
        $response = array(
            'status' => 'logged_in',
            'user_id' => $user['id'],
            'username' => $username
        );
        echo json_encode($response);
        exit;
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid username or password.'
        );
        echo json_encode($response);
        exit;
    }
}

// Check if user is trying to logout
if (isset($_GET['logout'])) {
    // Destroy session
    session_destroy();

    // Return JSON response with logout status
    $response = array(
        'status' => 'logged_out'
    );
    echo json_encode($response);
    exit;
}
?>