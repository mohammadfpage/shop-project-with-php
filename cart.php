<?php
session_start();

// بررسی ورود کاربر
if (!isset($_SESSION['user_id'])) {
    header("Location: users/login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// پاک کردن سبد خرید از دیتابیس
if (isset($_GET['clear_cart'])) {
    $sql = "DELETE FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// دریافت محصولات از سبد خرید کاربر
$sql = "SELECT p.title, p.price, c.quantity 
        FROM orders c 
        JOIN posts p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>سبد خرید</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    
<?php
echo $user_id;
?>
    <div class="container py-5">
        <h1 class="text-center">سبد خرید شما</h1>

        <?php if (empty($cart_items)): ?>
            <p class="text-center">سبد خرید شما خالی است.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>محصول</th>
                        <th>تعداد</th>
                        <th>قیمت واحد</th>
                        <th>مجموع قیمت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_price = 0;
                    foreach ($cart_items as $item): 
                        $item_total = $item['price'] * $item['quantity'];
                        $total_price += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'] * 25000); ?> تومان</td> 
                            <td><?php echo number_format($item_total * 25000); ?> تومان</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 style="margin-left:125px;" class="text-end">مجموع کل: <?php echo number_format($total_price * 25000); ?> تومان</h3>
            <a href="cart.php?clear_cart=1" class="btn btn-danger">پاک کردن سبد خرید</a>
        <?php endif; ?>
    </div>
</body>
</html>
