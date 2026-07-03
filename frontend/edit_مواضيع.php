**edit_مواضيع.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مواضيع.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواضيع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-2xl font-bold mb-4">تعديل مواضيع</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900 text-sm font-bold">العنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" value="<?php echo $data['title']; ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">الوصف</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" rows="5"><?php echo $data['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواضيع.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_مواضيع.php';
                        } else {
                            alert('Error: ' + response.message);
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
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مواضيع WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Output JSON response
echo json_encode($data);
?>


Note: This code assumes you have a MySQL database connection established in the `backend/مواضيع.php` file. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your actual database connection and query methods. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.