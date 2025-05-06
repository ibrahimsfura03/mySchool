<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
       include "../DB_connection.php";
       include "data/lecturer.php";
       include "data/subject.php";
       include "data/department.php";
       include "data/class.php";

       // Fetch lecturers
       $lecturers = getAllLecturers($conn); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Lecturers</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
    <a href="lecturer-add.php" class="btn btn-dark">Add New Lecturer</a>

    <form action="lecturer-search.php" class="mt-3 n-table" method="get">
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="searchKey" placeholder="Search...">
        <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
      </div>
    </form>

    <?php if (isset($_GET['error'])) { ?>
    <div class="alert alert-danger mt-3 n-table" role="alert"><?= $_GET['error'] ?></div>
    <?php } ?>

    <?php if (isset($_GET['success'])) { ?>
    <div class="alert alert-info mt-3 n-table" role="alert"><?= $_GET['success'] ?></div>
    <?php } ?>

    <?php if ($lecturers != 0) { ?>
      <div class="table-responsive">
  <table class="table  table-striped w-100 mt-3 n-table">
    <thead class="">
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Username</th>
        <th>Faculty</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
<?php 
  foreach ($lecturers as $lecturer) { 
?>
     <tr>
    <td><?= $lecturer['lecturer_id'] ?></td>
    <td><a href="lecturer-view.php?lecturer_id=<?= $lecturer['lecturer_id'] ?>"><?= $lecturer['fname'] ?></a></td>
    <td><?= $lecturer['lname'] ?></td>
    <td><?= $lecturer['username'] ?></td>
    <td>
      <?php
       $clss = getClassesByLecturer($lecturer['lecturer_id'], $conn);
       if ($clss) {
         foreach ($clss as $c) {
           echo htmlspecialchars($c['faculty'].' - '.$c['department']);
         }
       } else {
         echo '—';
       }
      ?>
    </td>
    <td>
      <a href="lecturer-edit.php?lecturer_id=<?= $lecturer['lecturer_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
      <a href="lecturer-delete.php?lecturer_id=<?= $lecturer['lecturer_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
    </td>
  </tr>
  <?php } ?>
</tbody>

      </table>
    </div>
    <?php } else { ?>
    <div class="alert alert-info w-450 m-5" role="alert">Empty!</div>
    <?php } ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
  <script>
    $(document).ready(function(){
      $("#navLinks li:nth-child(2) a").addClass('active');
    });
  </script>

</body>
</html>
<?php 
  } else {
    header("Location: ../login.php");
    exit;
  }
} else {
  header("Location: ../login.php");
  exit;
} 
?>
