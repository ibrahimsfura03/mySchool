<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/lecturer.php";
include "data/subject.php";
include "data/class.php";       // updated
include "data/department.php";

if (!isset($_GET['lecturer_id'])) {
    echo "Error: missing lecturer_id";
    exit;
}

$lecturer_id = intval($_GET['lecturer_id']);
$lecturer    = getLecturerById($lecturer_id, $conn);

if (!$lecturer) {
    echo "Error: lecturer not found (ID=$lecturer_id)";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin – Lecturer Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <div class="card mx-auto" style="width: 22rem;">
      <img src="../img/teacher-<?= htmlspecialchars($lecturer['gender']) ?>.png"
           class="card-img-top" alt="Profile image">
      <div class="card-body text-center">
        <h5 class="card-title">@<?= htmlspecialchars($lecturer['username']) ?></h5>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">First Name: <?= htmlspecialchars($lecturer['fname']) ?></li>
        <li class="list-group-item">Last Name: <?= htmlspecialchars($lecturer['lname']) ?></li>
        <li class="list-group-item">Username: <?= htmlspecialchars($lecturer['username']) ?></li>
        <li class="list-group-item">Employee #: <?= htmlspecialchars($lecturer['employee_number']) ?></li>
        <li class="list-group-item">Address: <?= htmlspecialchars($lecturer['address']) ?></li>
        <li class="list-group-item">Date of Birth: <?= htmlspecialchars($lecturer['date_of_birth']) ?></li>
        <li class="list-group-item">Phone: <?= htmlspecialchars($lecturer['phone_number']) ?></li>
        <li class="list-group-item">Qualification: <?= htmlspecialchars($lecturer['qualification']) ?></li>
        <li class="list-group-item">Email: <?= htmlspecialchars($lecturer['email_address']) ?></li>
        <li class="list-group-item">Gender: <?= htmlspecialchars($lecturer['gender']) ?></li>
        <li class="list-group-item">Joined: <?= htmlspecialchars($lecturer['date_of_joined']) ?></li>

        <li class="list-group-item">Levels:
  <?php
    $lvls = getLevelsByLecturer($lecturer_id, $conn);
    if (!empty($lvls)) {
      echo implode(", ", array_map('htmlspecialchars', $lvls));
    } else {
      echo "N/A";
    }
  ?>
</li>

        <li class="list-group-item">Subjects:
          <?php
            $subs = getSubjectsByLecturer($lecturer_id, $conn);
            if ($subs) {
              $codes = array_map(fn($s)=>htmlspecialchars($s['subject_code']), $subs);
              echo implode(", ", $codes);
            } else {
              echo "N/A";
            }
          ?>
        </li>

        <li class="list-group-item">Faculty:
          <?php
            $clss = getClassesByLecturer($lecturer_id, $conn);
            if ($clss) {
              $faculties = array_unique(array_map(fn($c)=>htmlspecialchars($c['faculty']), $clss));
              echo implode(", ", $faculties);
            } else {
              echo "N/A";
            }
          ?>
        </li>

        <li class="list-group-item">Department:
          <?php
            if (!empty($clss)) {
              $depts = array_unique(array_map(fn($c)=>htmlspecialchars($c['department']), $clss));
              echo implode(", ", $depts);
            } else {
              echo "N/A";
            }
          ?>
        </li>
      </ul>

      <div class="card-body text-center">
        <a href="lecturer.php" class="btn btn-dark">Go Back</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>document.querySelector("#navLinks li:nth-child(2) a").classList.add("active");</script>
</body>
</html>
