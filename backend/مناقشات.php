<?php
require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Check if user is logged in
if (!isset($_SESSION['loggedIn'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input parameters
    $id = isset($inputData['id']) ? intval($inputData['id']) : null;

    // Check if user is admin or trying to get their own data
    if ($userRole == 'admin' || $id == $_SESSION['userId']) {
        try {
            // Prepare SQL query
            $stmt = $pdo->prepare('SELECT * FROM مناقشات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Fetch data
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return data
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input parameters
    $title = isset($inputData['title']) ? trim($inputData['title']) : null;
    $description = isset($inputData['description']) ? trim($inputData['description']) : null;

    // Check if user is admin or trying to create new data
    if ($userRole == 'admin') {
        try {
            // Prepare SQL query
            $stmt = $pdo->prepare('INSERT INTO مناقشات (title, description, created_by) VALUES (:title, :description, :created_by)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':created_by', $_SESSION['userId']);
            $stmt->execute();

            // Return success message
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Data created successfully'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate and sanitize input parameters
    $id = isset($inputData['id']) ? intval($inputData['id']) : null;
    $title = isset($inputData['title']) ? trim($inputData['title']) : null;
    $description = isset($inputData['description']) ? trim($inputData['description']) : null;

    // Check if user is admin or trying to update their own data
    if ($userRole == 'admin' || $id == $_SESSION['userId']) {
        try {
            // Prepare SQL query
            $stmt = $pdo->prepare('UPDATE مناقشات SET title = :title, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            // Return success message
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Data updated successfully'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate and sanitize input parameters
    $id = isset($inputData['id']) ? intval($inputData['id']) : null;

    // Check if user is admin or trying to delete their own data
    if ($userRole == 'admin' || $id == $_SESSION['userId']) {
        try {
            // Prepare SQL query
            $stmt = $pdo->prepare('DELETE FROM مناقشات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return success message
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Data deleted successfully'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}