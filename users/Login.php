<?php
session_start(); // شروع سشن

// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

$error_message = "";
$success_message = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال به دیتابیس
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // بررسی وجود کاربر در دیتابیس بدون استفاده از prepared statements
    $sql = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // کاربر پیدا شد
        $row = $result->fetch_assoc();
        $access = $row['Access']; // مقدار فیلد Access

        // ذخیره اطلاعات کاربر در سشن
        $_SESSION['user_id'] = $row['id']; // ذخیره شناسه کاربر
        $_SESSION['username'] = $row['username']; // ذخیره نام کاربری

        // هدایت به صفحه مناسب بر اساس دسترسی کاربر
        if ($access == 1) {
            // کاربر ادمین است
            header("Location: ../panel/index.php"); // هدایت به پنل ادمین
        } elseif ($access == 0) {
            // کاربر معمولی است
            header("Location: ../shop.php"); // هدایت به صفحه‌ی قبلی
        } else {
            $error_message = "نوع دسترسی نامعتبر است.";
        }
        exit;
    } else {
        // کاربر پیدا نشد
        $error_message = "نام کاربری یا رمز عبور اشتباه است. لطفاً دوباره تلاش کنید.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ورود به حساب کاربری</title>
</head>

<body>
    <div class="container-md px-100">
        <div class="row text-center">
            <h2 class="my-2">ورود به حساب کاربری</h2>
            <p>آیا ثبت نام نکرده اید؟ <a href="register.php" class="text-decoration-none" >ثبت نام</a></p>

            <!-- form start -->
            <form class="m-auto col-xl-5 col-6 border rounded" action="login.php" method="post">
                <div class="form-group">
                    <label for="username" class="my-2">نام کاربری :</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password" class="my-2">رمز عبور :</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success border my-4">ورود</button>
            </form>
            <!-- form end -->

            <!-- نمایش پیام‌ها -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success mt-3" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
