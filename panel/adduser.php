
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

if(isset($_POST["sub1"])){
    $username = $_POST["username"];
    $email= $_POST["email"];
    $password = $_POST["password"];
    $Access = $_POST['Access'];

    $sql = "select * from users where username ='$username' and email = '$email' and password = '$password' and Access = '$Access' ";
    $result = $conn -> query($sql);
    if($result->num_rows==0){
      
        $sql = "insert into users(username,email,password,Access) values('$_POST[username]','$_POST[email]','$_POST[password]',$_POST[Access])";
    if($conn-> query ($sql) === true){
        print "<script>alert('با موفقیت یک کاربر اضافه کردید)</script>";
    }
    }
    else{
        print"<script>alert('این کاربر قبلا وجود داشته است')</script>";
        print"";
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
    <title>افزودن پست جدید</title>
  </head>
  <body>
    <section x-data="toggleSidebar" class="">
      <nav
        class="nav p-3 navbar navbar-expand-lg bg-light shadow fixed-top mb-5 transition"
      >
        <div class="container">
          <a class="navbar-brand" href="#">
            <img src="images/logo.png" alt="auto caren" width="50" />
            <span class="text-gray fw-bold">اتو کارن</span>
          </a>

          <button id="switchTheme"></button>
        </div>
      </nav>
      <section
        x-cloak
        class="sidebar bg-light transition"
        :class="open || 'inactive'"
      >
        <div
          class="d-flex align-items-center justify-content-between justify-content-lg-center"
        >
          <h4 class="fw-bold">auto caren</h4>
          <i @click="toggle" class="d-lg-none fs-1 bi bi-x"></i>
        </div>
        <div class="mt-4">
          <ul class="list-unstyled">
            <li class="sidebar-item ">
              <a class="sidebar-link" href="index.php">
                <i class="me-2 bi bi-grid-fill"></i>
                <span>داشبورد</span>
              </a>
            </li>

            <li x-data="dropdown" class="sidebar-item active">
              <div @click="toggle" class="sidebar-link">
                <i class="me-2 bi bi-shop"></i>
                <span>آگهی ها</span>
                <i class="ms-auto bi bi-chevron-down"></i>
              </div>
              <ul x-show="open" x-transition class="submenu">
                <li class="submenu-item">
                  <a href="addpost.php"> افزودن آگهی </a>
                </li>
                <li class="submenu-item">
                  <a href="posts.php"> آگهی ها</a>
                </li>
              </ul>
            </li>

            <li x-data="dropdown" class="sidebar-item">
              <div @click="toggle" class="sidebar-link">
                <i class="me-2 bi bi-people-fill"></i>
                <span>کاربران</span>
                <i class="ms-auto bi bi-chevron-down"></i>
              </div>
              <ul x-show="open" x-transition class="submenu">
                <li class="submenu-item">
                  <a href="users.php">لیست کاربران</a>
                </li>
                <li class="submenu-item">
                  <a href="adduser.php">ایجاد کاربران</a>
                </li>
                <li class="submenu-item">
                  <a href="users.php">ویرایش کاربران</a>
                </li>
              </ul>
            </li>

            <li x-data="dropdown" class="sidebar-item">
              <div @click="toggle" class="sidebar-link">
                <i class="me-2 bi bi-power"></i>
                <span><a class="text-decoration-none text-dark" href="../index.php">خروج</a></span>
                <i class="ms-auto bi"></i>
              </div>
              <ul x-show="open" x-transition class="submenu"></ul>
            </li>
          </ul>
        </div>
      </section>

      <section class="main" :class="open || 'active'">
        <div class="container">
          <div class="card card-primary bg-light shadow p-4 mt-5">
            <h1 class="text-gray h4 fw-bold">
              <i class="bi bi-plus-circle"></i>
              
              <span>افزودن آگهی</span>
            </h1>
            <form action="#" class="mt-4" method="POST">
              <div class="row">
                <div class="col-md-6">
                  <label for="username" class="text-gray-600 fw-bold"
                    >نام کاربری</label
                  >
                  <input name="username" id="username" type="text" class="form-control mt-2"/>
                </div>
                <div class="col-md-6">
                  <label for="email" class="text-gray-600 fw-bold"
                    > ایمیل</label>
                  
                  <input name="email" id="email" type="text" class="form-control mt-2"/>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-6">
                  <label for="password" class="text-gray-600 fw-bold"
                    >رمز عبور</label
                  >
                  <input name="password" id="password" type="text" class="form-control mt-2"/>
                </div>
                
              <select class="form-select  mb-3 m-auto mt-4" aria-label="Large select example" name="Access" style="margin:auto;">
            <option  selected class="m-auto" value="0">--نقش کاربر در سایت--</option>
            <option value="1">ادمین</option>
            <option value="0">کاربر عادی</option>
            
            </select>
              

              <div class="d-flex justify-content-end mt-5">
                <button name="sub1" type="submit" class="btn btn-primary btn-lg me-3 fs-6">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-send"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"
                    />
                  </svg>
                  <span>ثبت آگهی</span>
                </button>

              </div>
            </form>
          </div>
        </div>
      </section>
    </section>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>

    <script
      defer
      src="https://unpkg.com/alpinejs@3.3.4/dist/cdn.min.js"
    ></script>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script src="js/charts/chart1.js"></script>
    <script src="js/charts/chart2.js"></script>
    <script src="js/alpineComponents.js"></script>
    <script src="js/darkMode.js"></script>
  </body>
</html>
