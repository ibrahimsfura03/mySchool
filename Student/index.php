<?php 
session_start();
if (!isset($_SESSION['student_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";

$student_id = $_SESSION['student_id'];
$student = getStudentById($student_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student - Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <link rel="icon" href="../logo.png"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>
<?php if ($student !== 0): ?>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 col-lg-10 p-4">
        <h4 class="mb-4">Welcome <?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?> 👋</h4>

        <div class="row g-4">
          <!-- Grade Summary Card -->
          <div class="col-md-4">
            <a href="grade.php" class="text-decoration-none">
              <div class="card bg-success text-white text-center p-4 shadow">
                <h5><i class="fa fa-graduation-cap"></i> Grade Summary</h5>
              </div>
            </a>
          </div>

          <!-- Change Password Card -->
          <div class="col-md-4">
            <a href="pass.php" class="text-decoration-none">
              <div class="card bg-info text-white text-center p-4 shadow">
                <h5><i class="fa fa-lock"></i> Change Password</h5>
              </div>
            </a>
          </div>

          <!-- Student Details Card -->
          <div class="col-md-4">
            <a href="student_details.php" class="text-decoration-none">
              <div class="card bg-warning text-dark text-center p-4 shadow">
                <h5><i class="fa fa-user"></i> Student Details</h5>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(function() {
    $("#navLinks li:nth-child(1) a").addClass('active');
  });
</script>
</body>
</html>
