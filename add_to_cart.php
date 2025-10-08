<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: users/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price']; // نیازی به title نیست

    // بررسی اینکه آیا محصول در سبد خرید هست یا نه
    $sql = "SELECT id, quantity FROM orders WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // اگر محصول در سبد خرید است، تعداد را افزایش بده
        $new_quantity = $row['quantity'] + 1;
        $sql = "UPDATE orders SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_quantity, $row['id']);
        $stmt->execute();
    } else {
        // اگر محصول در سبد خرید نیست، آن را اضافه کن
        $sql = "INSERT INTO orders (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $quantity = 1; // مقدار اولیه برای quantity
        $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $product_price);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // بازگشت به صفحه فروشگاه
    header("Location: shop.php");
    exit();
}
?>
