<?php 
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/lecturer.php";

$lecturer_id = intval($_SESSION['lecturer_id']);
$lecturer    = getLecturerById($lecturer_id, $conn);
if (!$lecturer) {
    header("Location: logout.php?error=Invalid session");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lecturer – Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" href="../logo.png">
  <style>
    .dashboard-card {
      cursor: pointer;
      transition: transform .2s;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark vh-100 sidebar">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main content -->
      <div class="col-md-9 col-lg-10 p-4">
        <h4 class="mb-4">Welcome, <?= htmlspecialchars($lecturer['fname'] . ' ' . $lecturer['lname']) ?>!</h4>

        <!-- Dashboard Cards -->
        <div class="row g-4">
          <div class="col-md-3">
            <a href="myprofile.php" class="text-decoration-none">
              <div class="card bg-primary text-white text-center p-4 dashboard-card h-100">
                <i class="fa fa-user fa-2x mb-2"></i>
                <h6>My Profile</h6>
              </div>
            </a>
          </div>
          <div class="col-md-3">
            <a href="course.php" class="text-decoration-none">
              <div class="card bg-success text-white text-center p-4 dashboard-card h-100">
                <i class="fa fa-book fa-2x mb-2"></i>
                <h6>Courses</h6>
              </div>
            </a>
          </div>
          <div class="col-md-3">
            <a href="students.php" class="text-decoration-none">
              <div class="card bg-info text-white text-center p-4 dashboard-card h-100">
                <i class="fa fa-users fa-2x mb-2"></i>
                <h6>Students</h6>
              </div>
            </a>
          </div>
          <div class="col-md-3">
            <a href="pass.php" class="text-decoration-none">
              <div class="card bg-warning text-dark text-center p-4 dashboard-card h-100">
                <i class="fa fa-lock fa-2x mb-2"></i>
                <h6>Change Password</h6>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Highlight “Dashboard”
    document.querySelector("#navLinks li:nth-child(1) a").classList.add("active");
  </script>
</body>
</html>
