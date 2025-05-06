<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/subject.php";
include "data/department.php";

$courses = getAllSubjects($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Course</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <!-- always show the button -->
    <a href="course-add.php" class="btn btn-dark mb-3">Add New Course</a>

    <?php if (!empty($courses)): ?>
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
      <?php endif; ?>
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?=htmlspecialchars($_GET['success'])?></div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered n-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Course</th>
              <th>Course Code</th>
              <th>Department</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($courses as $i => $course): ?>
              <tr>
                <td><?=($i+1)?></td>
                <td><?=htmlspecialchars($course['subject'])?></td>
                <td><?=htmlspecialchars($course['subject_code'])?></td>
                <td>
                  <?php 
                    $d = getDepartmentById($course['department'], $conn);
                    echo htmlspecialchars($d['faculty'].' - '.$d['department']);
                  ?>
                </td>
                <td>
                  <a href="course-edit.php?course_id=<?=$course['subject_id']?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="course-delete.php?course_id=<?=$course['subject_id']?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No courses found.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
  <script>
    $(function(){
      $("#navLinks li:nth-child(8) a").addClass('active');
    });
  </script>
</body>
</html>
