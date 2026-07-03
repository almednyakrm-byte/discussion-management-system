<?php
// edit_مواضيع-مفتوحة.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_مواضيع-مفتوحة.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواضيع مفتوحة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-slate-900 text-indigo-500">
        <h1 class="text-3xl font-bold mb-4">تعديل مواضيع مفتوحة</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 rounded-lg hover:bg-indigo-700">حفظ</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/مواضيع-مفتوحة.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/مواضيع-مفتوحة.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    title: formData.get('title'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مواضيع-مفتوحة.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>