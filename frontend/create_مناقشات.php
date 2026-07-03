**create_مناقشات.php**

<?php
// Session validation
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="container mx-auto p-4 pt-6 mb-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة مناقشة جديدة</h2>

    <form id="create-form" class="space-y-6" method="POST" action="../backend/مناقشات.php">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="title">
                    عنوان المناقشة
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="title" name="title" type="text" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="description">
                    وصف المناقشة
                </label>
                <textarea class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" name="description" required></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            إضافة مناقشة جديدة
        </button>
    </form>
</div>

<?php
// Include footer
require_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مناقشات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_مناقشات.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes that you have jQuery and Bootstrap CSS included in your project. Also, make sure to replace `../backend/مناقشات.php` with the actual URL of your backend script.