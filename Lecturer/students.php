<?php 
session_start();
if (isset($_SESSION['lecturer_id'], $_SESSION['role']) && $_SESSION['role'] === 'Lecturer') {

    include "../DB_connection.php";
    include "data/department.php";    // for getDepartmentById()
    include "data/lecturer.php";      // for getLecturerById()
    
    $lecturer_id = $_SESSION['lecturer_id'];

    // 1) (we can ignore deptIds entirely for student-list)
    //    fetch levels this lecturer teaches
    $stmt2 = $conn->prepare(
      "SELECT level 
         FROM lecturer_levels 
        WHERE lecturer_id = ?"
    );
    $stmt2->execute([$lecturer_id]);
    $levels = $stmt2->fetchAll(PDO::FETCH_COLUMN);

    // 2) if no levels, no students
    if (empty($levels)) {
        $students = [];
    } else {
        // build placeholders for levels
        $lvlh = implode(',', array_fill(0, count($levels), '?'));

        // select students by level only
        $sql = "SELECT * 
                  FROM students 
                 WHERE level IN ($lvlh)";
        $stmt3 = $conn->prepare($sql);
        $stmt3->execute($levels);
        $students = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lecturer – My Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="../css/style.css" rel="stylesheet"/>
  <link rel="icon" href="../logo.png"/>
</head>
<body>
  <?php include "inc/navbar.php"; ?>

  <div class="container mt-5">
    <?php if (count($students) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered mt-3 n-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Level</th>
            <th>Department</th>
            <th>Student ID</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0; ?>
          <?php foreach ($students as $stu): 
                $dept = getDepartmentById((int)$stu['department_id'], $conn);
                $deptLabel = $dept
                          ? htmlspecialchars($dept['faculty'] . ' - ' . $dept['department'])
                          : 'N/A';
          ?>
            <tr>
              <th scope="row"><?= ++$i ?></th>
              <td><?= htmlspecialchars($stu['fname'] . ' ' . $stu['lname']) ?></td>
              <td><?= htmlspecialchars($stu['level'] ?? 'N/A') ?></td>
              <td><?= $deptLabel ?></td>
              <td><?= htmlspecialchars($stu['student_id']) ?></td>
              <td>
                <a href="student-view.php?student_id=<?= $stu['student_id'] ?>" class="btn btn-sm btn-primary">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info w-75 mx-auto" role="alert">
        No students found in your assigned levels.
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelector("#navLinks li:nth-child(3) a").classList.add('active');
  </script>
</body>
</html>
<?php
} else {
    header("Location: ../login.php");
    exit;
}
?>
