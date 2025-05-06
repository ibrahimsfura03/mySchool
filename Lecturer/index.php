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

// ── build subjects array ───────────────────────────────────────────────
$subs = [];
if (!empty($lecturer['subjects'])) {
  foreach (explode(",", $lecturer['subjects']) as $sid) {
    $S = getSubjectById($sid, $conn);
    if ($S) $subs[] = htmlspecialchars($S['subject_code']);
  }
}

// ── build one-element class array via the class_id from getLecturerById ─
$clss = getClassesByLecturer($lecturer_id, $conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lecturer – My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <div class="card mx-auto" style="width: 22rem;">
      <img src="../img/teacher-<?=htmlspecialchars($lecturer['gender'])?>.png"
           class="card-img-top" alt="Profile image">
      <div class="card-body text-center">
        <h5 class="card-title">@<?=htmlspecialchars($lecturer['username'])?></h5>
      </div>
      <ul class="list-group list-group-flush">
        <?php foreach ([
            'fname'=>'First Name','lname'=>'Last Name','username'=>'Username',
            'employee_number'=>'Employee #','address'=>'Address',
            'date_of_birth'=>'Date of Birth','phone_number'=>'Phone Number',
            'qualification'=>'Qualification','email_address'=>'Email Address',
            'gender'=>'Gender','date_of_joined'=>'Joined'
          ] as $f=>$label): ?>
          <li class="list-group-item">
            <?=$label?>: <?=htmlspecialchars($lecturer[$f])?>
          </li>
        <?php endforeach; ?>


      </ul>

      <div class="card-body text-center">
        <a href="logout.php" class="btn btn-secondary">Logout</a>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>document.querySelector("#navLinks li:nth-child(1) a").classList.add("active");</script>
</body>
</html>
