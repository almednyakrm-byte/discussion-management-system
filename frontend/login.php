<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #2f4f7f, #1a1d23);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #2f4f7f, #1a1d23);
            mix-blend-mode: multiply;
            opacity: 0.5;
        }
        
        .glassmorphic::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #2f4f7f, #1a1d23);
            mix-blend-mode: multiply;
            opacity: 0.5;
            filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic p-8 bg-white rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold text-slate-900 mb-4">Login</h2>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                <input type="text" id="username" name="username" class="block w-full px-4 py-2 mt-2 text-slate-900 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                <input type="password" id="password" name="password" class="block w-full px-4 py-2 mt-2 text-slate-900 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="w-full px-4 py-2 mt-2 text-slate-900 bg-indigo-500 hover:bg-indigo-600 rounded-lg focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500">Login</button>
        </form>
        <p class="text-sm text-gray-500 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-600">Register</a></p>
    </div>
    
    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses Tailwind CSS CDN for styling and includes a standard HTML input pattern validator to support Arabic and Latin characters. The form is submitted using AJAX with the Fetch API, and the response or error is handled dynamically with alerts. The direct link to the register page is also included.