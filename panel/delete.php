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

$Getid = $_GET['id'];

$delete = $conn->prepare("DELETE FROM posts WHERE id=?");

$delete->bind_param("i", $Getid);

$delete->execute();

header("location:posts.php");

?>
