<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if user is logged in
if ($userRole !== 'admin' && $userRole !== 'user') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET request
if ($method === 'GET') {
    // Get all topics
    $stmt = $pdo->prepare('SELECT * FROM مواضيع_مفتوحة');
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return all topics
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($topics);
}

// Handle POST request
if ($method === 'POST') {
    // Get topic data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate topic data
    if (!isset($data['title']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Sanitize topic data
    $title = htmlspecialchars($data['title']);
    $description = htmlspecialchars($data['description']);
    
    // Insert new topic
    $stmt = $pdo->prepare('INSERT INTO مواضيع_مفتوحة (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Return new topic ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
}

// Handle PUT request
if ($method === 'PUT') {
    // Get topic ID from URL
    $topicID = $_GET['id'];
    
    // Get topic data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate topic data
    if (!isset($data['title']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Sanitize topic data
    $title = htmlspecialchars($data['title']);
    $description = htmlspecialchars($data['description']);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Update topic
    $stmt = $pdo->prepare('UPDATE مواضيع_مفتوحة SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $topicID);
    $stmt->execute();
    
    // Return updated topic
    http_response_code(200);
    header('Content-Type: application/json');
    $stmt = $pdo->prepare('SELECT * FROM مواضيع_مفتوحة WHERE id = :id');
    $stmt->bindParam(':id', $topicID);
    $stmt->execute();
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Get topic ID from URL
    $topicID = $_GET['id'];
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Delete topic
    $stmt = $pdo->prepare('DELETE FROM مواضيع_مفتوحة WHERE id = :id');
    $stmt->bindParam(':id', $topicID);
    $stmt->execute();
    
    // Return deleted topic ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $topicID]);
}
?>