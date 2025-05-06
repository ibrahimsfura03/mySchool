<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";
include "data/department.php";

$students = getAllStudents($conn); // This now fetches students with their department

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <a href="student-add.php" class="btn btn-dark">Add New Student</a>

    <form action="student-search.php" method="get" class="mt-3 n-table">
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="searchKey" placeholder="Search...">
        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
      </div>
    </form>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger mt-3 n-table"><?= $_GET['error'] ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-info mt-3 n-table"><?= $_GET['success'] ?></div>
    <?php endif; ?>

    <?php if ($students): ?>
      <div class="table-responsive">
        <table class="table table-bordered mt-3 n-table">
          <thead>
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>Department</th>
              <th>Level</th> 
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($students as $i => $s): ?>
              <tr>
                <th><?= $i+1 ?></th>
                <td><?= $s['student_id'] ?></td>
                <td>
                  <a href="student-view.php?student_id=<?= $s['student_id'] ?>">
                    <?= htmlspecialchars($s['fname']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($s['lname']) ?></td>
                <td><?= htmlspecialchars($s['username']) ?></td>
                <td>
                  <?php
                    // Display the department name
                    echo htmlspecialchars($s['department'] ?? 'No department assigned');
                  ?>
                </td>
                <td><?= htmlspecialchars($s['level']   ?? '–') ?></td>
                <td>
                  <a href="student-edit.php?student_id=<?= $s['student_id'] ?>" class="btn btn-warning">Edit</a>
                  <a href="student-delete.php?student_id=<?= $s['student_id'] ?>" class="btn btn-danger">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info w-450 m-5">No students found.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function(){
      $("#navLinks li:nth-child(3) a").addClass('active');
    });
  </script>
</body>
</html>
