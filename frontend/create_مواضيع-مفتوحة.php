<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'مواضيع-مفتوحة';

// Page title
$page_title = 'Create مواضيع مفتوحة';

// Include header
include 'header.php';
?>

<main class="h-screen flex justify-center items-center">
    <div class="bg-slate-900 p-8 rounded-lg shadow-lg w-1/2">
        <h2 class="text-indigo-500 text-2xl font-bold mb-4"><?= $page_title ?></h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="title" class="block text-indigo-500 text-lg font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="bg-slate-900 border-indigo-500 text-indigo-500 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-indigo-500 text-lg font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="bg-slate-900 border-indigo-500 text-indigo-500 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 rounded-lg h-32"></textarea>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-indigo-500 text-lg font-bold mb-2">Category</label>
                <select id="category" name="category" class="bg-slate-900 border-indigo-500 text-indigo-500 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 rounded-lg">
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories from database
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 text-slate-900 hover:bg-indigo-700 hover:text-slate-100 py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>