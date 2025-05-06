<?php 
session_start();
if (isset($_SESSION['student_id']) && $_SESSION['role'] == 'Student') {
    include "../DB_connection.php";
    include "data/score.php";
    include "data/subject.php";
    include "data/student.php";
    include "data/department.php";

    $student_id = $_SESSION['student_id'];
    $student = getStudentById($student_id, $conn);
    $scores = getScoreById($student_id, $conn);
    $department = getDepartmentById($student['department_id'], $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Report Sheet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .report-section {
      border: 1px solid #000;
      padding: 20px;
      margin-bottom: 40px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #ccc;
    }
    .info-row strong {
      width: 30%;
    }
    .info-value {
      text-align: right;
      width: 65%;
    }
    .report-table {
      width: 100%;
      border-collapse: collapse;
    }
    .report-table th, .report-table td {
      border: 1px solid #000;
      padding: 10px;
      text-align: center;
    }
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body>
<?php include "inc/navbar.php"; ?>
<div class="container mt-5">

  <!-- Print Button -->
  <div class="text-end no-print mb-3">
    <button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
  </div>

  <!-- Part 1: Student Info Header -->
  <div class="report-section">
    <h4 class="text-center mb-4">STUDENT REPORT SHEET</h4>
    <div class="info-row"><strong>Full Name</strong><div class="info-value"><?= $student['fname'] . ' ' . $student['lname'] ?></div></div>
    <div class="info-row"><strong>Student ID</strong><div class="info-value"><?= $student['student_id'] ?></div></div>
    <div class="info-row"><strong>Gender</strong><div class="info-value"><?= $student['gender'] ?></div></div>
    <div class="info-row"><strong>Department</strong><div class="info-value"><?= $department['department'] ?></div></div>
    <div class="info-row"><strong>Faculty</strong><div class="info-value"><?= $department['faculty'] ?></div></div>
    <div class="info-row"><strong>Level</strong><div class="info-value"><?= $student['level'] ?></div></div>
    <div class="info-row"><strong>Date of Birth</strong><div class="info-value"><?= $student['date_of_birth'] ?></div></div>
    <div class="info-row"><strong>Email</strong><div class="info-value"><?= $student['email_address'] ?></div></div>
  </div>

  <!-- Part 2: Grade Table -->
  <?php if ($scores): ?>
    <?php 
      $checkYear = null;
    ?>
    <?php foreach ($scores as $score): ?>
      <?php 
        $subject = getSubjectById($score['subject_id'], $conn);
        $results = explode(',', trim($score['results']));
        $firstCA = $secondCA = $exam = 0;

        if (isset($results[0])) $firstCA = explode(' ', $results[0])[0];
        if (isset($results[1])) $secondCA = explode(' ', $results[1])[0];
        if (isset($results[2])) $exam = explode(' ', $results[2])[0];

        $total = $firstCA + $secondCA + $exam;
        $grade = gradeCalc($total);
      ?>

      <?php if ($score['year'] !== $checkYear): ?>
        <?php if ($checkYear !== null): ?>
          </tbody></table></div>
        <?php endif; ?>
        <?php $checkYear = $score['year']; ?>
        <div class="report-section">
          <h5 class="mb-3">Academic Year: <?= $score['year'] ?> | Semester: <?= $score['semester'] ?></h5>
          <div class="table-responsive">
            <table class="report-table">
              <thead class="table-light">
                <tr>
                  <th>Course Code</th>
                  <th>Course Title</th>
                  <th>1st CA</th>
                  <th>2nd CA</th>
                  <th>Exam</th>
                  <th>Total</th>
                  <th>Grade</th>
                </tr>
              </thead>
              <tbody>
      <?php endif; ?>
        <tr>
          <td><?= $subject['subject_code'] ?></td>
          <td><?= $subject['subject'] ?></td>
          <td><?= $firstCA ?></td>
          <td><?= $secondCA ?></td>
          <td><?= $exam ?></td>
          <td><?= $total ?></td>
          <td><?= $grade ?></td>
          <div class="mt-4 p-3 border">
            <h6 class="mb-3 text-center">Summary from Student Table</h6>
            <div class="row text-center">
              <div class="col-md-4 mb-2">
                <strong>1st CA</strong>
                <div><?= $student['first_ca'] ?? 'N/A' ?></div>
              </div>
              <div class="col-md-4 mb-2">
                <strong>2nd CA</strong>
                <div><?= $student['second_ca'] ?? 'N/A' ?></div>
              </div>
              <div class="col-md-4 mb-2">
                <strong>Exam</strong>
                <div><?= $student['exam'] ?? 'N/A' ?></div>
              </div>
            </div>
          </div>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div></div>
    <div class="mt-4 p-3 border">
  <h6 class="mb-3 text-center">Summary from Student Table</h6>
  <div class="row text-center">
    <div class="col-md-4 mb-2">
      <strong>1st CA</strong>
      <div><?= $student['first_ca'] ?? 'N/A' ?></div>
    </div>
    <div class="col-md-4 mb-2">
      <strong>2nd CA</strong>
      <div><?= $student['second_ca'] ?? 'N/A' ?></div>
    </div>
    <div class="col-md-4 mb-2">
      <strong>Exam</strong>
      <div><?= $student['exam'] ?? 'N/A' ?></div>
    </div>
  </div>
</div>

<?php else: ?>
  <?php
    $stmt = $conn->prepare("
        SELECT sss.*, sub.subject, sub.subject_code 
        FROM student_subject_scores sss
        INNER JOIN subjects sub ON sss.subject_id = sub.subject_id
        WHERE sss.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $studentScores = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php if ($studentScores): ?>
    <div class="report-section">
      <h5 class="mb-3">Course Results</h5>
      <div class="table-responsive">
        <table class="report-table">
          <thead class="table-light">
            <tr>
              <th>Course Code</th>
              <th>Course Title</th>
              <th>1st CA</th>
              <th>2nd CA</th>
              <th>Exam</th>
              <th>Total</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $grandTotal = 0;
              $numSubjects = 0;
              foreach ($studentScores as $score):
                $firstCA = (int)($score['first_ca'] ?? 0);
                $secondCA = (int)($score['second_ca'] ?? 0);
                $exam = (int)($score['exam'] ?? 0);
                $total = $firstCA + $secondCA + $exam;
                $grade = gradeCalc($total);
                $grandTotal += $total;
                $numSubjects++;
            ?>
            <tr>
              <td><?= $score['subject_code'] ?></td>
              <td><?= $score['subject'] ?></td>
              <td><?= $firstCA ?></td>
              <td><?= $secondCA ?></td>
              <td><?= $exam ?></td>
              <td><?= $total ?></td>
              <td><?= $grade ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Summary Box -->
    <?php $cgpa = $numSubjects > 0 ? ($grandTotal / ($numSubjects * 100)) * 5 : 0.0; ?>
    <div class="mt-4 p-3 border" style="background-color: rgba(0, 123, 255, 0.1);">
      <div class="row">
        <div class="col text-start"><strong>Grand Total:</strong> <?= $grandTotal ?> / <?= $numSubjects * 100 ?></div>
        <div class="col text-end"><strong>CGPA:</strong> <?= number_format($cgpa, 2) ?> / 5.00</div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center my-4">No scores available yet.</div>
  <?php endif; ?>
<?php endif; ?>


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
?>
