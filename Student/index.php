<?php 
session_start();
if (!isset($_SESSION['student_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";
include "data/subject.php";
include "data/section.php";
include "data/department.php";  // <— our department lookup

$student_id = $_SESSION['student_id'];
$student    = getStudentById($student_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student - Home</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <?php if ($student !== 0): ?>
    <div class="container mt-5">
      <div class="card" style="width: 22rem;">
        <img src="../img/student-<?=htmlspecialchars($student['gender'])?>.png" class="card-img-top" alt="">
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
                  if ($d) {
                      echo htmlspecialchars($d['faculty'] . ' - ' . $d['department']);
                  } else {
                      echo 'Not assigned';
                  }
              } else {
                  echo 'Not assigned';
              }
            ?>
          </li>

          <li class="list-group-item">Section:
            <?php 
              if (!empty($student['section_id'])) {
                  $s = getSectionById($student['section_id'], $conn);
                  echo htmlspecialchars($s['section']);
              } else {
                  echo 'Not assigned';
              }
            ?>
          </li>

          <li class="list-group-item">Level:
            <?= !empty($student['level']) ? htmlspecialchars($student['level']) : 'Not assigned' ?>
          </li>

          <li class="list-group-item">Parent first name: <?=htmlspecialchars($student['parent_fname'])?></li>
          <li class="list-group-item">Parent last name: <?=htmlspecialchars($student['parent_lname'])?></li>
          <li class="list-group-item">Parent phone number: <?=htmlspecialchars($student['parent_phone_number'])?></li>
        </ul>
      </div>
    </div>
  <?php else: ?>
    <?php header("Location: index.php"); exit; ?>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
  <script>
    $(function(){
      $("#navLinks li:nth-child(1) a").addClass('active');
    });
  </script>
</body>
</html>
