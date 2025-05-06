<?php
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['student_id'], $_GET['subject_id'])) {
    header("Location: students.php");
    exit;
}

include "../DB_connection.php";

// Get student and subject details
$student_id = (int)$_GET['student_id'];
$subject_id = (int)$_GET['subject_id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $conn->prepare("SELECT * FROM subjects WHERE subject_id = ?");
$stmt2->execute([$subject_id]);
$subject = $stmt2->fetch(PDO::FETCH_ASSOC);

// Fetch existing scores if any
$stmt3 = $conn->prepare("SELECT * FROM student_subject_scores WHERE student_id = ? AND subject_id = ?");
$stmt3->execute([$student_id, $subject_id]);
$score = $stmt3->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Convert empty strings to NULL and sanitize inputs
  $first_ca = $_POST['first_ca'] !== '' ? (int)$_POST['first_ca'] : null;
  $second_ca = $_POST['second_ca'] !== '' ? (int)$_POST['second_ca'] : null;
  $exam = $_POST['exam'] !== '' ? (int)$_POST['exam'] : null;

  try {
      if ($score) {
          // Update existing
          $stmt = $conn->prepare("UPDATE student_subject_scores SET first_ca = ?, second_ca = ?, exam = ? WHERE student_id = ? AND subject_id = ?");
          $stmt->execute([$first_ca, $second_ca, $exam, $student_id, $subject_id]);
      } else {
          // Insert new
          $stmt = $conn->prepare("INSERT INTO student_subject_scores (student_id, subject_id, first_ca, second_ca, exam) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$student_id, $subject_id, $first_ca, $second_ca, $exam]);
      }

      header("Location: student-view.php?student_id=$student_id&success=Scores updated");
      exit;
  } catch (PDOException $e) {
      echo "<div class='alert alert-danger'>Error saving scores: " . htmlspecialchars($e->getMessage()) . "</div>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Grades – <?=htmlspecialchars($student['fname'])?> <?=htmlspecialchars($student['lname'])?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <div class="container mt-5">
  <h3>Edit Grades for <?=htmlspecialchars($subject['subject'])?></h3>
  <p><strong>Student:</strong> <?=htmlspecialchars($student['fname'] . ' ' . $student['lname'])?></p>
    <form method="post">
        <div class="mb-3">
            <label for="first_ca" class="form-label">First CA</label>
            <input type="number" class="form-control" id="first_ca" name="first_ca" min="0" max="20" value="<?=htmlspecialchars($score['first_ca'] ?? '')?>">
        </div>
        <div class="mb-3">
            <label for="second_ca" class="form-label">Second CA</label>
            <input type="number" class="form-control" id="second_ca" name="second_ca" min="0" max="20" value="<?=htmlspecialchars($score['second_ca'] ?? '')?>">
        </div>
        <div class="mb-3">
            <label for="exam" class="form-label">Exam</label>
            <input type="number" class="form-control" id="exam" name="exam" min="0" max="60" value="<?=htmlspecialchars($score['exam'] ?? '')?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</body>
</html>
