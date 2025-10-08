<?php
session_start(); // شروع سشن

// اگر کاربر لاگین نکرده باشد، به صفحه لاگین هدایت شود
if (!isset($_SESSION['user_id'])) {
    header("Location: users/login.php");
    exit();
}

// اگر سبد خرید خالی باشد، به صفحه سبد خرید بازگردانده شود
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی خطا
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ذخیره اطلاعات خرید در دیتابیس
foreach ($_SESSION['cart'] as $item) {
    $user_id = $_SESSION['user_id']; // شناسه کاربر از سشن
    $product_id = $item['id']; // شناسه محصول
    $quantity = $item['quantity']; // تعداد محصول از سبد خرید
    $price = $item['price']; // قیمت محصول

    // درج اطلاعات خرید در جدول orders
    $sql = "INSERT INTO orders (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $price);

    if (!$stmt->execute()) {
        die("خطا در ذخیره اطلاعات خرید: " . $stmt->error);
    }
}

// پاک کردن سبد خرید
unset($_SESSION['cart']);

// نمایش پیام موفقیت
echo "<script>alert('خرید شما با موفقیت تکمیل شد!');</script>";
header("Location: shop.php"); // هدایت به صفحه فروشگاه
exit();
?>
