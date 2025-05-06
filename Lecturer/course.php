<?php 
session_start();
if (isset($_SESSION['lecturer_id'], $_SESSION['role']) && $_SESSION['role'] === 'Lecturer') {
    include "../DB_connection.php";
    include "data/subject.php";     // ← load subject helpers
    include "data/department.php";  // ← for getDepartmentById()
    include "data/lecturer.php";

    $lecturer_id = $_SESSION['lecturer_id'];
    $lecturer    = getLecturerById($lecturer_id, $conn);

    // fetch only the subjects this lecturer teaches
    $courses = getSubjectsByLecturer($lecturer_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lecturer - My Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <?php if (!empty($courses)): ?>
      <div class="table-responsive">
        <table class="table table-bordered mt-3 n-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Course</th>
              <th>Course Code</th>
              <th>Department</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($courses as $i => $c): 
               $dept = getDepartmentById($c['department'], $conn);
               $deptLabel = $dept
                          ? htmlspecialchars($dept['faculty'].' - '.$dept['department'])
                          : 'N/A';
            ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($c['subject']) ?></td>
                <td><?= htmlspecialchars($c['subject_code']) ?></td>
                <td><?= $deptLabel ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">You are not assigned to teach any courses.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function(){
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
?>
