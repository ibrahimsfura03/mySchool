<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/department.php";
       $departments = getAllDepartments($conn);

       // preserve old inputs on error
       $fname = $_GET['fname']            ?? '';
       $lname = $_GET['lname']            ?? '';
       $uname = $_GET['uname']            ?? '';
       $address = $_GET['address']        ?? '';
       $email = $_GET['email']            ?? '';
       $pfn = $_GET['pfn']                ?? '';
       $pln = $_GET['pln']                ?? '';
       $ppn = $_GET['ppn']                ?? '';
       $selectedDept = $_GET['department'] ?? '';
       $level        = $_GET['level']      ?? 'ONE';  // default to ONE
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<?php include "inc/navbar.php"; ?>

<div class="container mt-5">
  <a href="student.php" class="btn btn-dark">Go Back</a>

  <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-add.php">
    <h3>Add New Student</h3><hr>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">First name</label>
      <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($fname) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Last name</label>
      <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($lname) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Address</label>
      <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="text" class="form-control" name="email_address" value="<?= htmlspecialchars($email) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Date of birth</label>
      <input type="date" class="form-control" name="date_of_birth">
    </div>
    <div class="mb-3">
      <label class="form-label">Gender</label><br>
      <input type="radio" name="gender" value="Male" checked> Male
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" name="gender" value="Female"> Female
    </div>
    <hr>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($uname) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="pass" id="passInput">
        <button class="btn btn-secondary" id="gBtn">Random</button>
      </div>
    </div>
    <hr>

    <div class="mb-3">
      <label class="form-label">Parent first name</label>
      <input type="text" class="form-control" name="parent_fname" value="<?= htmlspecialchars($pfn) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Parent last name</label>
      <input type="text" class="form-control" name="parent_lname" value="<?= htmlspecialchars($pln) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Parent phone number</label>
      <input type="text" class="form-control" name="parent_phone_number" value="<?= htmlspecialchars($ppn) ?>">
    </div>
    <hr>

    <div class="mb-3">
      <label class="form-label">Department</label>
      <select class="form-select" name="department" required>
        <option value="" disabled <?= $selectedDept==''?'selected':'' ?>>Choose Department</option>
        <?php foreach ($departments as $d): ?>
          <option value="<?= $d['department_id'] ?>"
            <?= $selectedDept==$d['department_id']?'selected':'' ?>>
            <?= htmlspecialchars($d['faculty'] . ' – ' . $d['department']) ?>
          </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Level</label>
      <select class="form-select" name="level">
        <option value="ONE"    <?= $level==='ONE'    ? 'selected' : '' ?>>ONE</option>
        <option value="TWO-DE" <?= $level==='TWO-DE' ? 'selected' : '' ?>>TWO-DE</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
  </form>
</div>

<script>
  document.getElementById('gBtn').addEventListener('click', function(e){
    e.preventDefault();
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let pass = '';
    for(let i=0;i<4;i++) pass += chars.charAt(Math.floor(Math.random()*chars.length));
    document.getElementById('passInput').value = pass;
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
