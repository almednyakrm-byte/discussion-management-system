**create_مشاركين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة مشارك</h2>
        <form id="create-participant-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="name">اسم المشارك</label>
                    <input class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="email">بريد إلكتروني</label>
                    <input class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" type="email" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="phone">رقم الهاتف</label>
                    <input class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone" type="tel" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="role">دور المشارك</label>
                    <select class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="role" required>
                        <option value="">اختر دور</option>
                        <option value="مشارك">مشارك</option>
                        <option value="مستشار">مستشار</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-participant-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مشاركين.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_مشاركين.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**مشاركين.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['role'])) {
    // Prepare SQL query
    $sql = "INSERT INTO participants (name, email, phone, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['role']);
    // Execute query
    if ($stmt->execute()) {
        // Return success response
        echo json_encode(array('success' => true, 'message' => 'مشارك جديد تم إضافته بنجاح'));
    } else {
        // Return error response
        echo json_encode(array('success' => false, 'message' => 'خطأ في إضافة المشارك'));
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Return error response
    echo json_encode(array('success' => false, 'message' => 'بيانات المشارك غير صالحة'));
}
?>


Note: This code assumes that you have a database connection established and a table named `participants` with columns `name`, `email`, `phone`, and `role`. You should modify the code to fit your specific database schema and requirements.