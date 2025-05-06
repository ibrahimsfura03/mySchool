<?php
session_start();
if ($_SESSION['role']!=='Admin') exit;
include "../../DB_connection.php";
include "../data/admin.php";

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$uname = trim($_POST['username'] ?? '');
$pass  = $_POST['password'] ?? '';

if (!$fname||!$lname||!$uname||!$pass) {
  header("Location: ../admin-add.php?error=All fields are required"); exit;
}
if (!adminUnameUnique($uname,$conn)) {
  header("Location: ../admin-add.php?error=Username already taken"); exit;
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO admin(fname,lname,username,password) VALUES(?,?,?,?)");
$stmt->execute([$fname,$lname,$uname,$hash]);

header("Location: ../admins.php?success=Admin created");
