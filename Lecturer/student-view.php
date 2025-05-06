<?php
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
  header("Location: ../login.php");
  exit;
}
if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
  header("Location: students.php");
  exit;
}

include "../DB_connection.php";
include "data/department.php";
include "data/subject.php";

$student_id = (int)$_GET['student_id'];
// 1) load student
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
  echo "<div class='alert alert-danger'>Student not found.</div>";
  exit;
}

// 2) load subjects for department
$subjects = getSubjectByDepartment($student['department_id'], $conn);

// 3) fetch all scores for this student into a map
$stmt2 = $conn->prepare(
  "SELECT subject_id, first_ca, second_ca, exam 
     FROM student_subject_scores 
    WHERE student_id = ?"
);
$stmt2->execute([$student_id]);
$scoresMap = [];
while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  $scoresMap[$r['subject_id']] = $r;
}

// 4) compute grand total & CGPA
$grandTotal    = 0;
$numSubjects   = count($subjects);
foreach ($subjects as $sub) {
  $sc = $scoresMap[$sub['subject_id']] ?? ['first_ca'=>0,'second_ca'=>0,'exam'=>0];
  $grandTotal += ($sc['first_ca'] + $sc['second_ca'] + $sc['exam']);
}
// avoid division by zero
if ($numSubjects > 0) {
  $cgpa = round( ($grandTotal / ($numSubjects * 100)) * 5.0 , 2);
} else {
  $cgpa = 0.00;
}
// 5) store CGPA back to students table
$stmt3 = $conn->prepare("UPDATE students SET cgpa = ? WHERE student_id = ?");
$stmt3->execute([$cgpa, $student_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student View – <?=htmlspecialchars($student['fname']." ".$student['lname'])?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
  <h3 class="mb-4">Student Details</h3>
    <p><strong>Name:</strong> <?=htmlspecialchars($student['fname']." ".$student['lname'])?></p>
    <p><strong>Level:</strong> <?=htmlspecialchars($student['level'])?></p>
    <p><strong>Department:</strong> 
      <?=htmlspecialchars( ($d=getDepartmentById($student['department_id'],$conn)) ? $d['faculty']." - ".$d['department'] : 'N/A' )?>
    </p>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-outline-primary" onclick="window.print()">🖨️ Print Report</button>
    </div>

    <h4 class="mt-4">Courses & Scores</h4>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Course</th>
            <th>Code</th>
            <th>First CA</th>
            <th>Second CA</th>
            <th>Exam</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($subjects as $i=>$sub):
            $sc = $scoresMap[$sub['subject_id']] ?? ['first_ca'=>0,'second_ca'=>0,'exam'=>0];
            $total = $sc['first_ca'] + $sc['second_ca'] + $sc['exam'];
        ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($sub['subject']) ?></td>
            <td><?= htmlspecialchars($sub['subject_code']) ?></td>
            <td><?= $sc['first_ca'] ?></td>
            <td><?= $sc['second_ca'] ?></td>
            <td><?= $sc['exam'] ?></td>
            <td><?= $total ?></td>
            <td>
              <a href="student-grade.php?student_id=<?=$student_id?>&subject_id=<?=$sub['subject_id']?>"
                 class="btn btn-sm btn-primary">Edit</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        </table> <!-- Make sure this is closing your table first -->

<!-- Summary Box Below Table -->
<div style="margin-top: 20px; padding: 15px; background-color: rgba(0, 123, 255, 0.1); border-left: 5px solid #007bff; border-radius: 5px;" class="d-flex justify-content-between align-items-center">
  <div><strong>Grand Total:</strong> <?= $grandTotal ?> / <?= $numSubjects * 100 ?></div>
  <div><strong>CGPA:</strong> <?= number_format($cgpa, 2) ?></div>
</div>

</tfoot>

      </table>
    </div>
  </div>
</body>
</html>
