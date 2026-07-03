<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة المناقشات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 h-screen">
    <div class="flex justify-between items-center p-4">
        <h1 class="text-3xl text-indigo-500">نظام إدارة المناقشات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl text-white mb-4">مرحباً بكم</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-900 p-4 rounded">
                    <h3 class="text-lg text-white mb-2">إحصائيات</h3>
                    <div id="stats-grid"></div>
                </div>
                <div class="bg-slate-900 p-4 rounded">
                    <h3 class="text-lg text-white mb-2">روابط سريعة</h3>
                    <ul>
                        <li><a href="#" class="text-white hover:text-indigo-500">مناقشات</a></li>
                        <li><a href="#" class="text-white hover:text-indigo-500">مواضيع</a></li>
                        <li><a href="#" class="text-white hover:text-indigo-500">مواضيع مفتوحة</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statElement = document.createElement('div');
                    statElement.classList.add('bg-slate-900', 'p-4', 'rounded', 'mb-4');
                    statElement.innerHTML = `
                        <h3 class="text-lg text-white mb-2">${stat.title}</h3>
                        <p class="text-white">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statElement);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and JavaScript to fetch stats dynamically from the backend API. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats grid is populated dynamically using JavaScript API calls from the backend files.