**list_مناقشات.php**

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
    <title>مناقشات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B5CFF;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-indigo-500 hover:text-white">تسجيل خروج</a>
            </div>
        </header>
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl text-indigo-500">مناقشات</h1>
            <a href="create_مناقشات.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="bg-gray-800 text-white p-2 rounded" placeholder="بحث...">
            <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-700">
            <thead>
                <tr>
                    <th class="border border-gray-700 px-4 py-2">عنوان</th>
                    <th class="border border-gray-700 px-4 py-2">تاريخ</th>
                    <th class="border border-gray-700 px-4 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const records = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مناقشات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-700 px-4 py-2">${record.title}</td>
                                <td class="border border-gray-700 px-4 py-2">${record.date}</td>
                                <td class="border border-gray-700 px-4 py-2">
                                    <a href="edit_مناقشات.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/مناقشات.php')
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-700 px-4 py-2">${record.title}</td>
                                <td class="border border-gray-700 px-4 py-2">${record.date}</td>
                                <td class="border border-gray-700 px-4 py-2">
                                    <a href="edit_مناقشات.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                    <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            }
        });

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مناقشات.php', {
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
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/مناقشات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM مناقشات WHERE title LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM مناقشات";
}

// Fetch records
$result = $conn->query($query);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output records
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM مناقشات WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}
?>