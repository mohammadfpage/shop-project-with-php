<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbpanel";

// ایجاد اتصال به پایگاه داده
$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی خطا در اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$getid = $_GET['id'] ?? 0;

// انتخاب اطلاعات پست
$select = $conn->prepare("SELECT * FROM posts WHERE id=?");
$select->bind_param("i", $getid);
$select->execute();
$result = $select->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("پست پیدا نشد.");
}

if (isset($_POST['sub'])) {
    $title = $_POST['title'];
    $caption = $_POST['caption'];
    $writer = $_POST['writer'];
    $target_file = $post['image']; // مسیر تصویر پیش‌ فرض

    // بررسی آپلود تصویر جدید
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            if (!move_uploaded_file($image["tmp_name"], $target_file)) {
                die("خطا در ذخیره تصویر.");
            }
        } else {
            die("نوع فایل غیرمجاز است. فقط jpg, jpeg, png, gif مجاز هستند.");
        }
    }

    // به‌روزرسانی اطلاعات در دیتابیس
    $update = $conn->prepare("UPDATE posts SET title=?, caption=?, writer=?, image=? WHERE id=?");
    $update->bind_param("ssssi", $title, $caption, $writer, $target_file, $getid);

    // چک کردن نتیجه عملیات
    if ($update->execute()) {
        echo "پست با موفقیت ویرایش شد.";
    } else {
        // اگر خطایی در ویرایش وجود داشت، پیام خطا را چاپ کنید
        echo "خطا در ویرایش پست: " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.rtl.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="css/panel.css" />
    <link rel="stylesheet" href="css/main.css">
    <title>ویرایش پست</title>
  </head>
  <body>
    <section x-data="toggleSidebar" class="">

      <!-- Sidebar and Header Code ... -->

      <section class="main" :class="open || 'active'">
        <div class="container">
          <div class="card card-primary bg-light shadow p-4 mt-5">
            <h1 class="text-gray h4 fw-bold">
              <i class="bi bi-plus-circle"></i>
              <span>ویرایش آگهی</span>
            </h1>

            <!-- فرم ویرایش پست -->
            <?php if (!empty($post)): ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label for="name" class="text-gray-600 fw-bold">نام آگهی</label>
                        <input name="title" id="name" type="text" class="form-control mt-2" value="<?= $post['title']; ?>" />
                    </div>

                    <div class="col-md-6">
                        <label for="image" class="text-gray-600 fw-bold">لینک عکس</label>
                        <input name="image" id="image" type="file" class="form-control mt-2" />
                        <small>تصویر فعلی: <?= $post['image']; ?></small>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="text" class="text-gray-600 fw-bold">متن آگهی</label>
                    <textarea
                        name="caption"
                        id="text"
                        class="form-control mt-2"
                        cols="30"
                        rows="10"
                    ><?= $post['caption']; ?></textarea>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label for="category" class="text-gray-600 fw-bold">نویسنده</label>
                        <select name="writer" class="form-select mt-2" id="category">
                            <option value="<?= $post['writer']; ?>">محمد</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-5">
                    <button name="sub" type="submit" class="btn btn-primary btn-lg me-3 fs-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                        <span>ویرایش</span>
                    </button>
                </div>
            </form>
            <?php else: ?>
                <p>هیچ پستی یافت نشد.</p>
            <?php endif; ?>

          </div>
        </div>
      </section>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>
