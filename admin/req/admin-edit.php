<?php
session_start();
if ($_SESSION['role']!=='Admin') exit;
include "../../DB_connection.php";
include "../data/admin.php";

$id    = intval($_POST['admin_id'] ?? 0);
$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$uname = trim($_POST['username'] ?? '');
$pass  = $_POST['password'] ?? '';

if (!$id||!$fname||!$lname||!$uname) {
  header("Location: ../admin-edit.php?admin_id=$id&error=Missing required fields"); exit;
}
if (!adminUnameUnique($uname,$conn,$id)) {
  header("Location: ../admin-edit.php?admin_id=$id&error=Username already taken"); exit;
}

$params = [$uname, $fname, $lname];
$sql = "UPDATE admin SET username=?, fname=?, lname=?";
if ($pass!=='') {
  $sql .= ", password=?";
  $params[] = password_hash($pass, PASSWORD_DEFAULT);
}
$sql .= " WHERE admin_id=?";
$params[] = $id;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

header("Location: ../admin-edit.php?admin_id=$id&success=Saved");
