<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($inputData)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Define database table name
$tableName = 'مواضيع';

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $sql = 'SELECT * FROM ' . $tableName;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $title = $pdo->quote($inputData['title']);
    $content = $pdo->quote($inputData['content']);

    // Prepare SQL query
    $sql = 'INSERT INTO ' . $tableName . ' (title, content) VALUES (' . $title . ', ' . $content . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Output data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'موضوع added successfully']);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = $pdo->quote($inputData['id']);
    $title = $pdo->quote($inputData['title']);
    $content = $pdo->quote($inputData['content']);

    // Prepare SQL query
    $sql = 'UPDATE ' . $tableName . ' SET title = ' . $title . ', content = ' . $content . ' WHERE id = ' . $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'موضوع updated successfully']);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = $pdo->quote($inputData['id']);

    // Prepare SQL query
    $sql = 'DELETE FROM ' . $tableName . ' WHERE id = ' . $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'موضوع deleted successfully']);
    exit;
}