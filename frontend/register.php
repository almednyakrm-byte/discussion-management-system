<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .submit-btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Register</h2>
        </div>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" title="Username should contain only letters, numbers and spaces">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="submit-btn" type="submit">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. The form fields are validated using HTML5 validation attributes and a regular expression pattern. The form is submitted via AJAX to the `auth.php` file in the backend directory. If the registration is successful, the user is redirected to the login page. If the registration fails, an error message is displayed.