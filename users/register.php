<?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

$error_message = "";
$success_message = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // اعتبارسنجی ورودی‌ها
    if ($password != $password2) {
        $error_message = "رمز عبور با تکرار آن مطابقت ندارد!";
    } else {
        // بررسی تکراری بودن username و email
        $checkSql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($checkSql);

        if ($result->num_rows > 0) {
            $error_message = "ایمیل و یا نام کاربری تکراری است. لطفاً اطلاعات را درست وارد کنید.";
        } else {
            // اگر تکراری نبود، کاربر ثبت نام می‌شود در دیتابیس
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "ثبت‌ نام با موفقیت انجام شد!";
            } else {
                $error_message = "خطا در ثبت اطلاعات. لطفاً دوباره تلاش کنید.";
            }
        }
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

    <title>ثبت نام </title>
    
</head>

<body >
    
    <div class="container-md px-100">
        <div class="row text-center">
            <h2 class="my-2">ثبت نام </h2> 
            <p>قبلا ثبت نام کرده اید؟ <a href="login.php" class="text-decoration-none" >  ورود به سایت</a></p>

            <!-- form start -->
            <form class="m-auto col-xl-5 col-6 rounded border" action="register.php" method="post">
                <div class="form-group">
                    <label for="username" class="my-2">نام کاربری :</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email" class="my-2">ایمیل:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="my-2">رمز عبور :</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password2" class="my-2">تکرار رمز عبور :</label>
                    <input type="password" class="form-control" id="password2" name="password2" required>
                </div>
                <button type="submit" class="btn btn-success my-4 border">ثبت نام</button>
            </form>
            <!-- form end -->

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
