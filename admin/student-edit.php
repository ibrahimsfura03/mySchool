<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && isset($_GET['student_id'])) {

  if ($_SESSION['role'] == 'Admin') {
    include "../DB_connection.php";
    include "data/subject.php";
    include "data/department.php";
    include "data/student.php";

    $subjects    = getAllSubjects($conn);
    $departments = getAllDepartments($conn);

    $student_id = $_GET['student_id'];
    $student    = getStudentById($student_id, $conn);

    if ($student == 0) {
      header("Location: student.php");
      exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Edit Student</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
</head>
<body>

<?php include "inc/navbar.php"; ?>

<div class="container mt-5">
  <a href="student.php" class="btn btn-dark">Go Back</a>

  <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-edit.php">
    <h3>Edit Student Info</h3><hr>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">First name</label>
      <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($student['fname']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Last name</label>
      <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($student['lname']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Address</label>
      <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($student['address']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="text" class="form-control" name="email_address" value="<?= htmlspecialchars($student['email_address']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Date of birth</label>
      <input type="date" class="form-control" name="date_of_birth" value="<?= htmlspecialchars($student['date_of_birth']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Gender</label><br>
      <input type="radio" name="gender" value="Male" <?= $student['gender']=='Male' ? 'checked':'' ?>> Male
      &nbsp;&nbsp;
      <input type="radio" name="gender" value="Female" <?= $student['gender']=='Female' ? 'checked':'' ?>> Female
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($student['username']) ?>">
    </div>

    <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">

    <div class="mb-3">
    <label class="form-label">Level</label>
    <select name="level" class="form-select" required>
      <option value="ONE" <?= $student['level'] === 'ONE' ? 'selected' : '' ?>>ONE</option>
      <option value="TWO" <?= $student['level'] === 'TWO' ? 'selected' : '' ?>>TWO</option>
      <option value="THREE" <?= $student['level'] === 'THREE' ? 'selected' : '' ?>>THREE</option>
      <option value="FOUR" <?= $student['level'] === 'FOUR' ? 'selected' : '' ?>>FOUR</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-select" required>
      <option value="">Select Department</option>
      <?php foreach ($departments as $department): ?>
        <option value="<?= $department['department_id'] ?>" <?= $student['department_id'] == $department['department_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($department['faculty'] . ' - ' . $department['department']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>


    <br><hr>

    <div class="mb-3">
      <label class="form-label">Parent first name</label>
      <input type="text" class="form-control" name="parent_fname" value="<?= htmlspecialchars($student['parent_fname']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Parent last name</label>
      <input type="text" class="form-control" name="parent_lname" value="<?= htmlspecialchars($student['parent_lname']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Parent phone number</label>
      <input type="text" class="form-control" name="parent_phone_number" value="<?= htmlspecialchars($student['parent_phone_number']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
  </form>

  <!-- password‐change form unchanged… -->

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
  } else {
    header("Location: student.php");
    exit;
  }
} else {
  header("Location: student.php");
  exit;
}
?>
