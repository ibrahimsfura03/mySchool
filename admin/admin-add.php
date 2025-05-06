<?php 
session_start();
if (!isset($_SESSION['admin_id'],$_SESSION['role']) || $_SESSION['role']!=='Admin') {
    header("Location: ../login.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Admin – lecturer-edit</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css"><link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
    <h3>Add New Admin</h3>
    <?php if(isset($_GET['error'])):?>
      <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
    <?php endif;?>
    <form method="post" action="req/admin-add.php" class="shadow p-4 bg-white">
      <div class="mb-3"><label class="form-label">First Name</label>
        <input name="fname" class="form-control" required>
      </div>
      <div class="mb-3"><label class="form-label">Last Name</label>
        <input name="lname" class="form-control" required>
      </div>
      <div class="mb-3"><label class="form-label">Username</label>
        <input name="username" class="form-control" required>
      </div>
      <div class="mb-3"><label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Create Admin</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>$(function(){ $("#navLinks li:nth-child(8) a").addClass('active'); });</script>
</body>
</html>
