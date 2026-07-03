**create_مواضيع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Create form data array
$data = array();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Validate required fields
    if (empty($title) || empty($description)) {
        $data['error'] = 'Please fill in all required fields.';
    } else {
        // Insert new record
        $sql = "INSERT INTO مواضيع (title, description) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data['success'] = 'موضوع added successfully!';
            header('Location: list_مواضيع.php');
            exit;
        } else {
            $data['error'] = 'Failed to add موضوع. Please try again.';
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موضوع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
        }
        .btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl text-slate-900 mb-4">إضافة موضوع</h2>
        <form id="create-form" method="post">
            <div class="form-group">
                <label for="title">عنوان الموضوع</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">وصف الموضوع</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn" name="submit">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مواضيع.php',
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            alert(data.success);
                            window.location.href = 'list_مواضيع.php';
                        } else if (data.error) {
                            alert(data.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواضيع.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Validate required fields
    if (empty($title) || empty($description)) {
        echo json_encode(array('error' => 'Please fill in all required fields.'));
        exit;
    } else {
        // Insert new record
        $sql = "INSERT INTO مواضيع (title, description) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(array('success' => 'موضوع added successfully!'));
        } else {
            echo json_encode(array('error' => 'Failed to add موضوع. Please try again.'));
        }
    }
}

// Close database connection
mysqli_close($conn);
?>