**list_مشاركين.php**

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
    <title>مشاركين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
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
        .search-bar button[type="submit"] {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مشاركين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مشاركين.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button type="submit" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>عنوان</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مشاركين.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchQuery
                    }
                })
                .then(response => response.json())
                .then(data => {
                    recordsContainer.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.عنوان}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><a href="edit_مشاركين.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/مشاركين.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                recordsContainer.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم}</td>
                        <td>${record.عنوان}</td>
                        <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><a href="edit_مشاركين.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                    `;
                    recordsContainer.appendChild(row);
                });
            })
            .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المشارك؟')) {
                fetch('../backend/مشاركين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        loadRecords();
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, a table to display records, and a search bar to filter elements in real-time. The `searchRecords()` function is called when the search button is clicked, and it fetches the records from the backend using the `fetch()` API. The `loadRecords()` function is called initially to load the records from the backend. The `deleteRecord()` function is called when the delete button is clicked, and it sends a DELETE request to the backend to delete the record.