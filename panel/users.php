<?php

session_start();
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

$count = 0;
$select = $conn->prepare("SELECT * from users");
$select->execute();
$result = $select->get_result();
$registers = $result->fetch_all(MYSQLI_ASSOC);

?>




<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/bootstrap.rtl.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="css/panel.css" />
  <link rel="stylesheet" href="css/main.css">
  <title> مقالات </title>
</head>

<body>
  <section x-data="toggleSidebar" class="">
    <nav class="nav p-3 navbar navbar-expand-lg bg-light shadow fixed-top mb-5 transition">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="images/logo.png" alt="Codeyad Logo" width="50" />
          <span class="text-gray fw-bold">اتو کارن</span>
        </a>

        <button id="switchTheme"></button>
      </div>
    </nav>
    <section x-cloak class="sidebar bg-succse transition" :class="open || 'inactive'">
      <div class="d-flex align-items-center justify-content-between justify-content-lg-center">
        <h4 class="fw-bold">پنل شخصی ادمین</h4>
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
              <span>آگهی</span>
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
                <a href="#">لیست کاربران</a>
              </li>
              <li class="submenu-item">
                <a href="adduser.php">ایجاد کاربران</a>
              </li>
              <li class="submenu-item">
                <a href="#">ویرایش کاربران</a>
              </li>
            </ul>
          </li>

          <li x-data="dropdown" class="sidebar-item">
            <div @click="toggle" class="sidebar-link">
              <i class="me-2 bi bi-power"></i>
              <span> خروج</span>
              <i class="ms-auto bi"></i>
            </div>
            <ul x-show="open" x-transition class="submenu"></ul>
          </li>
        </ul>
      </div>
    </section>

    <section class="main" :class="open || 'active'">
      <div class="container ">
        <div class="card card-succsess bg-light shadow p-4 mt-5 ">
          <h6 class="text-gray h6 fw-bold">
            <i class="bi bi-plus-circle"></i>

           
              <table class="table table-striped ">
                <thead class="">
                  <tr class="" >
                    <!-- <th scope="col">#</th> -->
                    <th scope="col">نام کاربری </th>
                    <th scope="col">ایمیل</th>
                    <th scope="col">رمز عبور</th>
                    <th scope="col">نفش کاربر</th>
                  </tr>
                </thead>
                <?php foreach($registers as $register):  ?>
                <tbody>
                  <tr>
                    <!-- <th scope="row">
                      <?= $count+=1;
                      ?>
                    </th> -->
                 <td>
                  <?=  $register["username"]  ?>
                 </td>

                 <td>
                 <?=  $register["email"]  ?>
                 </td>

                 <td>
                 <?=  $register["password"]  ?>
                 </td>
              
                 <td>
                 <?php  
                if($register["Access"]==1)
                    echo "admin";
                else
                    echo "user";
                ?>
                 </td>

                 <!-- <td>
                  <a href="delete.php?id=<?= $register["id"];?>" class="btn btn-danger"> dell</a>
                 </td> -->

                 <td>
                  <a href="edituser.php?id=<?= $register["id"];?>" class="btn btn-warning "> edit</a>
                 </td> 

                  </tr>

                </tbody>
                
                
                <?php endforeach; ?>
                <thead>
                <th scope="col">تعداد کل کاربران</th>

                </thead>
                <tbody>
                <td>
                  <?= $count;  ?>
                 </td>
                </tbody>
                
                
              </table>
              <p>
                
              </p>

            
          
        </div>
      </div>
    </section>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>

  <script defer src="https://unpkg.com/alpinejs@3.3.4/dist/cdn.min.js"></script>

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