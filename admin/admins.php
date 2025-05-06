<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">
    <?= htmlspecialchars($_GET['success']) ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_GET['error']) ?>
  </div>
<?php endif; ?>

<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}
include "../DB_connection.php";
include "data/admin.php";
$admins = getAllAdmins($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admins – mySchool</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php include "inc/navbar.php"; ?>
  <div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Admins</h3>
  <div class="d-flex gap-2">
    <a href="admin-edit.php?admin_id=<?= $_SESSION['admin_id'] ?>" class="btn btn-warning">
      <i class="fa fa-edit"></i> Edit My Profile
    </a>
    <a href="admin-add.php" class="btn btn-primary">
      <i class="fa fa-plus"></i> Add New Admin
    </a>
  </div>
</div>

    <?php if($admins): ?>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Username</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($admins as $index => $admin) { ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($admin['fname'].' '.$admin['lname']) ?></td>
                <td><?= htmlspecialchars($admin['username']) ?></td>
                <td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No admins found.</div>
    <?php endif; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>$(function(){ $("#navLinks li:nth-child(8) a").addClass('active'); });</script>
</body>
</html>
