**list_مواضيع-مفتوحة.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مواضيع مفتوحة</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة المواضيع المفتوحة</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مواضيع مفتوحة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواضيع-مفتوحة.php'">إضافة جديد</button>
        <div class="search-bar mt-4">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>العنوان</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['title'] ?></td>
                        <td>
                            <a href="edit_مواضيع-مفتوحة.php?id=<?= $record['id'] ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetchRecords(search);
        }

        function fetchRecords(search = '') {
            const url = '../backend/مواضيع-مفتوحة.php';
            const params = new URLSearchParams({
                search,
            });
            const response = fetch(`${url}?${params.toString()}`);
            return response.json();
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch(`../backend/مواضيع-مفتوحة.php?delete&id=${id}`, {
                    method: 'DELETE',
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error(error);
                });
            }
        }
    </script>
</body>
</html>

<?php
function fetchRecords() {
    $url = '../backend/مواضيع-مفتوحة.php';
    $response = file_get_contents($url);
    return json_decode($response, true);
}
?>


**backend/مواضيع-مفتوحة.php**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'title' => 'موضوع مفتوح 1'),
    array('id' => 2, 'title' => 'موضوع مفتوح 2'),
    array('id' => 3, 'title' => 'موضوع مفتوح 3'),
);

// Search functionality
$search = $_GET['search'] ?? '';
if ($search) {
    $records = array_filter($records, function ($record) use ($search) {
        return strpos($record['title'], $search) !== false;
    });
}

// Delete record
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    unset($records[array_search($records[$id], $records)]);
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);
?>