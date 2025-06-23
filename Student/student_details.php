<?php 
session_start();
if (!isset($_SESSION['student_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";
include "data/subject.php";
include "data/department.php";

$student_id = $_SESSION['student_id'];
$student    = getStudentById($student_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Details</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <link rel="icon" href="../logo.png"/>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main content -->
      <div class="col-md-9 col-lg-10 py-4">
        <?php if ($student !== 0): ?>
          <h4 class="mb-4">Student Profile</h4>
          <div class="card mx-auto" style="max-width: 700px;">
            <img src="../img/student-<?=htmlspecialchars($student['gender'])?>.png" 
     class="mx-auto d-block my-3 rounded-circle" 
     alt="Student" 
     style="width: 100px; height: 100px; object-fit: cover;">

            <div class="card-body">
              <h5 class="card-title text-center">@<?=htmlspecialchars($student['username'])?></h5>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">First name: <?=htmlspecialchars($student['fname'])?></li>
              <li class="list-group-item">Last name: <?=htmlspecialchars($student['lname'])?></li>
              <li class="list-group-item">Username: <?=htmlspecialchars($student['username'])?></li>
              <li class="list-group-item">Address: <?=htmlspecialchars($student['address'])?></li>
              <li class="list-group-item">Date of birth: <?=htmlspecialchars($student['date_of_birth'])?></li>
              <li class="list-group-item">Email address: <?=htmlspecialchars($student['email_address'])?></li>
              <li class="list-group-item">Gender: <?=htmlspecialchars($student['gender'])?></li>
              <li class="list-group-item">Date of joined: <?=htmlspecialchars($student['date_of_joined'])?></li>
              <li class="list-group-item">Department:
                <?php 
                  if (!empty($student['department_id'])) {
                      $d = getDepartmentById($student['department_id'], $conn);
                      echo $d ? htmlspecialchars($d['faculty'] . ' - ' . $d['department']) : 'Not assigned';
                  } else {
                      echo 'Not assigned';
                  }
                ?>
              </li>
              <li class="list-group-item">Level: <?= !empty($student['level']) ? htmlspecialchars($student['level']) : 'Not assigned' ?></li>
              <li class="list-group-item">Parent first name: <?=htmlspecialchars($student['parent_fname'])?></li>
              <li class="list-group-item">Parent last name: <?=htmlspecialchars($student['parent_lname'])?></li>
              <li class="list-group-item">Parent phone number: <?=htmlspecialchars($student['parent_phone_number'])?></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
