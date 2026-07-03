**edit_مناقشات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مناقشات.php?id=' . $id;
$existingRecord = json_decode(file_get_contents($url), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مناقشة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 font-bold text-lg mb-4">تعديل مناقشة</h2>
        <form id="edit-form" class="space-y-4">
            <div class="flex flex-col">
                <label for="title" class="text-slate-900 font-bold text-sm mb-2">عنوان المناقشة:</label>
                <input type="text" id="title" name="title" class="bg-gray-100 border border-gray-300 rounded-md py-2 px-4 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div class="flex flex-col">
                <label for="content" class="text-slate-900 font-bold text-sm mb-2">محتوى المناقشة:</label>
                <textarea id="content" name="content" class="bg-gray-100 border border-gray-300 rounded-md py-2 px-4 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="5"><?= $existingRecord['content'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مناقشات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مناقشات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    die('Error: ID not set');
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'title' => 'عنوان المناقشة',
    'content' => 'محتوى المناقشة'
);

// Return JSON response
echo json_encode($existingRecord);