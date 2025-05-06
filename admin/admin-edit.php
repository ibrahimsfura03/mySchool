<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/admin.php";

$id = intval($_GET['admin_id'] ?? 0);
$admin = getAdminById($id, $conn);

// Check if the admin exists
if (!$admin) { 
    header("Location: admins.php");
    exit; 
}

// Ensure the logged-in admin can only edit their own details or other admins
if ($_SESSION['admin_id'] != $admin['admin_id'] && $_SESSION['role'] !== 'Admin') {
    header("Location: admins.php?error=Unauthorized access");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Admin – School</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css"><link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
    <h3>Edit Admin</h3>
    <?php if(isset($_GET['error'])):?>
      <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
    <?php elseif(isset($_GET['success'])):?>
      <div class="alert alert-success"><?=htmlspecialchars($_GET['success'])?></div>
    <?php endif;?>
    <form method="post" action="req/admin-edit.php" class="shadow p-4 bg-white">
      <input type="hidden" name="admin_id" value="<?=$id?>">
      <div class="mb-3"><label class="form-label">First Name</label>
        <input name="fname" class="form-control" value="<?=htmlspecialchars($admin['fname'])?>" required>
      </div>
      <div class="mb-3"><label class="form-label">Last Name</label>
        <input name="lname" class="form-control" value="<?=htmlspecialchars($admin['lname'])?>" required>
      </div>
      <div class="mb-3"><label class="form-label">Username</label>
        <input name="username" class="form-control" value="<?=htmlspecialchars($admin['username'])?>" required>
      </div>
      <div class="mb-3"><label class="form-label">New Password <small>(leave blank to keep)</small></label>
        <input name="password" type="password" class="form-control">
      </div>
      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>$(function(){ $("#navLinks li:nth-child(8) a").addClass('active'); });</script>
</body>
</html>
