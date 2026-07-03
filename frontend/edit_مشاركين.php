**edit_مشاركين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
$id = $_GET['id'];

// Fetch the existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مشاركين.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مشارك</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل مشارك</h2>
        <form id="edit-participant-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم المشارك:</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-900 text-sm font-bold mb-2">بريد إلكتروني:</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-participant-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مشاركين.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**مشاركين.php (backend)**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Error: ID is required.';
    exit;
}

// Connect to the database
$conn = new PDO('dsn', 'username', 'password');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the existing record details
$stmt = $conn->prepare('SELECT * FROM participants WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$existingRecord = $stmt->fetch();

// Return the existing record details as JSON
echo json_encode($existingRecord);

// Close the database connection
$conn = null;
?>