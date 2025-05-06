<?php
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['student_id'])) {
    echo "Missing student_id";
    exit;
}

include "../DB_connection.php";

$student_id = (int)$_GET['student_id'];
$stmt = $conn->prepare("SELECT fname,lname,first_ca,second_ca,exam FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$stu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stu) {
    echo "Student not found";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Scores – <?= htmlspecialchars($stu['fname']." ".$stu['lname']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="../css/style.css" rel="stylesheet"/>
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5" style="max-width:400px;">
    <h3>Edit Scores for <?= htmlspecialchars($stu['fname']." ".$stu['lname']) ?></h3>
    <form action="req/update_scores.php" method="post" class="mt-4">
      <input type="hidden" name="student_id" value="<?= $student_id ?>">
      <div class="mb-3">
        <label class="form-label">First CA</label>
        <input type="number" name="first_ca" class="form-control"
               value="<?= htmlspecialchars($stu['first_ca'] ?? '') ?>" min="0" max="20" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Second CA</label>
        <input type="number" name="second_ca" class="form-control"
               value="<?= htmlspecialchars($stu['second_ca'] ?? '') ?>" min="0" max="20" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Exam</label>
        <input type="number" name="exam" class="form-control"
               value="<?= htmlspecialchars($stu['exam'] ?? '') ?>" min="0" max="60" required>
      </div>
      <button type="submit" class="btn btn-primary">Update Scores</button>
      <a href="students.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
