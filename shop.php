<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

// اتصال به پایگاه داده
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت محصولات
$posts = [];
$sql = "SELECT id, title, caption, price, writer, image FROM posts";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// دریافت تعداد محصولات در سبد خرید (از دیتابیس)
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(quantity) AS total FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $cart_count = $row['total'] ?? 0;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>علی استایل - فروشگاه آنلاین</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <style>
        .add-to-cart-btn {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }
        .add-to-cart-btn:hover {
            background-color: #0056b3;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- نوار بالای صفحه -->
    <nav class="navbar navbar-expand-lg navbar-light shadow ">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.html">
                علی استایل
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between"
                id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">خانه</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.html">درباره ما</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shop.php">فروشگاه</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">تماس با ما</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users/register.php">ورود به سایت</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal"
                        data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="cart.php">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span
                            class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"><?php echo $cart_count; ?></span>
                    </a>

                    <a class="nav-icon position-relative text-decoration-none" href="">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span
                            class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                    </a>


                </div>
            </div>

        </div>
    </nav>

    <!-- محتوای صفحه -->
    <div class="container py-5">
        <div class="row">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 product-wap rounded-0">
                            <div class="card rounded-0">
                                <img class="card-img rounded-0 img-fluid fixed" src="panel/<?php echo htmlspecialchars($post['image']); ?>" style="width:350px;height:342px;margin-right:30px">
                            </div>
                            <div class="card-body">
                                <a href="shop-single.html" class="h3 text-decoration-none"><?php echo htmlspecialchars($post['title']); ?></a>
                                <p><?php echo htmlspecialchars($post['caption']); ?></p>
                                <p class="text-center mb-0"><?php echo number_format($post['price'] * 25000); ?> تومان </p> <!-- نمایش قیمت -->
                                <form action="add_to_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $post['id']; ?>">
                                    <input type="hidden" name="product_title" value="<?php echo $post['title']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $post['price']; ?>">
                                    <input type="hidden" name="product_writer" value="<?php echo $post['writer']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-success mt-3 mb-3 add-to-cart-btn">افزودن به سبد خرید</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>محصولی در فروشگاه موجود نیست.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- اسکریپت‌ها -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
