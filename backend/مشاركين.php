<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get all participants
    $stmt = $pdo->prepare('SELECT * FROM participants');
    $stmt->execute();
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return participants
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($participants);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

    // Insert participant
    $stmt = $pdo->prepare('INSERT INTO participants (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return participant ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole != 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

    // Update participant
    $stmt = $pdo->prepare('UPDATE participants SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Participant updated successfully']);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole != 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete participant
    $stmt = $pdo->prepare('DELETE FROM participants WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Participant deleted successfully']);
}

// Return error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}