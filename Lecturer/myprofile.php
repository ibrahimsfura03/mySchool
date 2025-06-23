<?php 
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/lecturer.php";
include "data/subject.php";
include "data/class.php";
include "data/department.php";

$lecturer_id = intval($_SESSION['lecturer_id']);
$lecturer    = getLecturerById($lecturer_id, $conn);
if (!$lecturer) {
    header("Location: logout.php?error=Invalid session");
    exit;
}

// Build subject codes array
$subs = [];
if (!empty($lecturer['subjects'])) {
  foreach (explode(",", $lecturer['subjects']) as $sid) {
    $S = getSubjectById($sid, $conn);
    if ($S) $subs[] = htmlspecialchars($S['subject_code']);
  }
}

// Get departments instead of "classes"
// $deptNames = [];
// // $deptList = getDepartmentsByLecturer($lecturer_id, $conn);
// foreach ($deptList as $d) {
//   $deptNames[] = htmlspecialchars($d['faculty'] . ' - ' . $d['department']);
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lecturer – My Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
  <style>
    .profile-card {
      max-width: 400px;
      margin: auto;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .profile-card .card-img-top {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin: 20px auto 0;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark sidebar">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main content -->
      <div class="col-md-9 col-lg-10 p-4">
        <h4 class="mb-4">My Profile</h4>
        <div class="card profile-card">
          <img src="../img/teacher-<?=htmlspecialchars($lecturer['gender'])?>.png" class="card-img-top" alt="Profile image">
          <div class="card-body text-center">
            <h5 class="card-title">@<?=htmlspecialchars($lecturer['username'])?></h5>
          </div>
          <ul class="list-group list-group-flush">
            <?php foreach ([
              'fname' => 'First Name', 'lname' => 'Last Name',
              'username' => 'Username', 'employee_number' => 'Employee #',
              'address' => 'Address', 'date_of_birth' => 'Date of Birth',
              'phone_number' => 'Phone Number', 'qualification' => 'Qualification',
              'email_address' => 'Email Address', 'gender' => 'Gender',
              'date_of_joined' => 'Joined'
            ] as $field => $label): ?>
              <li class="list-group-item">
                <strong><?= $label ?>:</strong> <?= htmlspecialchars($lecturer[$field]) ?>
              </li>
            <?php endforeach; ?>

            <?php if (!empty($subs)): ?>
              <li class="list-group-item">
                <strong>Subjects:</strong> <?= implode(", ", $subs) ?>
              </li>
            <?php endif; ?>

            <?php if (!empty($deptNames)): ?>
              <li class="list-group-item">
                <strong>Departments:</strong> <?= implode(", ", $deptNames) ?>
              </li>
            <?php endif; ?>
          </ul>
          <div class="card-body text-center">
            <a href="../logout.php" class="btn btn-secondary">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelector("#navLinks li:nth-child(4) a").classList.add("active");
  </script>
</body>
</html>
