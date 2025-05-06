<?php
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/subject.php";
include "data/department.php";

$subjects    = getAllSubjects($conn);
$departments = getAllDepartments($conn);

$fname            = $_POST['fname']            ?? '';
$lname            = $_POST['lname']            ?? '';
$uname            = $_POST['username']         ?? '';
$address          = $_POST['address']          ?? '';
$en               = $_POST['employee_number']  ?? '';
$pn               = $_POST['phone_number']     ?? '';
$qf               = $_POST['qualification']    ?? '';
$email            = $_POST['email_address']    ?? '';
$date_of_birth    = $_POST['date_of_birth']    ?? '';
$subjectsSelected = $_POST['subjects']         ?? [];
$department_id    = $_POST['department_id']    ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Add Lecturer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
</head>
<body>
<?php include "inc/navbar.php"; ?>

<div class="container mt-5">
  <a href="lecturer.php" class="btn btn-dark">Go Back</a>

  <form action="req/lecturer-add.php" method="post" class="shadow p-3 mt-5 form-w">
    <h3>Add New Lecturer</h3><hr>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">First Name</label>
      <input type="text" name="fname" class="form-control" value="<?= htmlspecialchars($fname) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Last Name</label>
      <input type="text" name="lname" class="form-control" value="<?= htmlspecialchars($lname) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($uname) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group">
        <input type="text" name="pass" id="passInput" class="form-control" required>
        <button class="btn btn-secondary" id="gBtn">Random</button>
        <button type="button" class="btn btn-info" id="togglePassword">Show</button>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Address</label>
      <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($address) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Employee Number</label>
      <input type="number" name="employee_number" class="form-control" value="<?= htmlspecialchars($en) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone Number</label>
      <input type="tel" name="phone_number" class="form-control" value="<?= htmlspecialchars($pn) ?>" required pattern="[0-9]{10}">
    </div>

    <div class="mb-3">
      <label class="form-label">Qualification</label>
      <input type="text" name="qualification" class="form-control" value="<?= htmlspecialchars($qf) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" name="email_address" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Gender</label><br>
      <input type="radio" name="gender" value="Male" checked> Male 
      &nbsp;&nbsp;
      <input type="radio" name="gender" value="Female"> Female
    </div>

    <div class="mb-3">
      <label class="form-label">Date of Birth</label>
      <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($date_of_birth) ?>" required>
    </div>

    <div class="mb-3">
  <label class="form-label">Levels</label><br>
  <div class="row row-cols-4">
    <div class="col">
      <input type="checkbox" name="levels[]" value="ONE"
             <?= in_array('ONE', $_POST['levels'] ?? []) ? 'checked' : '' ?>>
      ONE
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="TWO"
             <?= in_array('TWO', $_POST['levels'] ?? []) ? 'checked' : '' ?>>
      TWO
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="THREE"
             <?= in_array('THREE', $_POST['levels'] ?? []) ? 'checked' : '' ?>>
      THREE
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="FOUR"
             <?= in_array('FOUR', $_POST['levels'] ?? []) ? 'checked' : '' ?>>
      FOUR
    </div>
  </div>
</div>



<div class="mb-3">
  <label class="form-label">Subjects</label>
  <div class="row row-cols-5">
    <?php $subjects = is_array($subjects) ? $subjects : []; ?>
    <?php foreach ($subjects as $subject): ?>
      <div class="col">
        <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>"
               <?= in_array($subject['subject_id'], $subjectsSelected) ? 'checked' : '' ?>>
        <?= htmlspecialchars($subject['subject']) ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>


    <div class="mb-3">
      <label class="form-label">Department</label>
      <select name="department_id" class="form-control" required>
        <option value="">-- Select Department --</option>
        <?php foreach ($departments as $d): ?>
          <option value="<?= $d['department_id'] ?>" <?= $department_id == $d['department_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($d['faculty'] . ' - ' . $d['department']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Lecturer</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('gBtn').addEventListener('click', e => {
    e.preventDefault();
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let pass = '';
    for (let i = 0; i < 6; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('passInput').value = pass;
  });
  document.getElementById('togglePassword').addEventListener('click', function(){
    const f = document.getElementById('passInput');
    if (f.type === 'password') { f.type = 'text'; this.textContent='Hide'; }
    else { f.type = 'password'; this.textContent='Show'; }
  });
</script>
</body>
</html>
