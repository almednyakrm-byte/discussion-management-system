**list_مواضيع.php**

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
    <title>مواضيع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
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
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مواضيع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواضيع.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['title']; ?></td>
                        <td><?php echo $record['description']; ?></td>
                        <td><?php echo $record['created_at']; ?></td>
                        <td>
                            <a href="edit_مواضيع.php?id=<?php echo $record['id']; ?>" class="text-indigo-500">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
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
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مواضيع.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const recordsElement = document.getElementById('records');
                        recordsElement.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.description}</td>
                                <td>${record.created_at}</td>
                                <td>
                                    <a href="edit_مواضيع.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        });
                    });
            } else {
                fetchRecords();
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مواضيع.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        function fetchRecords() {
            fetch('../backend/مواضيع.php')
                .then(response => response.json())
                .then(data => {
                    const recordsElement = document.getElementById('records');
                    recordsElement.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td>${record.created_at}</td>
                            <td>
                                <a href="edit_مواضيع.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsElement.appendChild(row);
                    });
                });
        }
    </script>
</body>
</html>


**backend/مواضيع.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'title' => 'عنوان السجل 1',
    'description' => 'وصف السجل 1',
    'created_at' => '2022-01-01 12:00:00'
);
$records[] = array(
    'id' => 2,
    'title' => 'عنوان السجل 2',
    'description' => 'وصف السجل 2',
    'created_at' => '2022-01-02 12:00:00'
);

// Search query
$searchQuery = $_GET['search'] ?? '';

if ($searchQuery) {
    $records = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record['title'], $searchQuery) !== false || strpos($record['description'], $searchQuery) !== false;
    });
}

// Delete record
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
    exit;
}

// Output records
echo json_encode($records);