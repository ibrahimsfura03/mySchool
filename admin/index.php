<?php 
session_start();
if (!isset($_SESSION['admin_id'],$_SESSION['role']) || $_SESSION['role']!=='Admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Home</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
  <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
  <div class="col">
    <a href="lecturer.php" class="btn btn-dark w-100 py-4">
      <i class="fa fa-user-md fs-1"></i><br>Lecturers
    </a>
  </div>
  <div class="col">
    <a href="student.php" class="btn btn-dark w-100 py-4">
      <i class="fa fa-graduation-cap fs-1"></i><br>Students
    </a>
  </div>
  <div class="col">
    <a href="class.php" class="btn btn-dark w-100 py-4">
      <i class="fa fa-cubes fs-1"></i><br>Profile
    </a>
  </div>
  <div class="col">
    <a href="department.php" class="btn btn-dark w-100 py-4">
      <i class="fa fa-level-up fs-1"></i><br>Department
    </a>
  </div>
  <div class="col">
    <a href="course.php" class="btn btn-dark w-100 py-4">
      <i class="fa fa-book fs-1"></i><br>Course
    </a>
  </div>
  <div class="col">
    <a href="settings.php" class="btn btn-primary w-100 py-4">
      <i class="fa fa-cogs fs-1"></i><br>Settings
    </a>
  </div>
</div>

<!-- 👇 Separate row for Admins and Logout -->
<div class="row g-4 text-center mt-2">
  <div class="col-6">
    <a href="admins.php" class="btn btn-success w-100 py-4">
      <i class="fa fa-users fs-1"></i><br>Admins
    </a>
  </div>
  <div class="col-6">
    <a href="../logout.php" class="btn btn-warning w-100 py-4">
      <i class="fa fa-sign-out fs-1"></i><br>Logout
    </a>
  </div>
</div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function(){
      // highlight Dashboard nav
      $("#navLinks li:nth-child(1) a").addClass('active');
    });
  </script>
</body>
</html>
