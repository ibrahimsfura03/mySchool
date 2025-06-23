<?php
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['searchKey'])) {
    $search_key = $_GET['searchKey'];
    include "../DB_connection.php";
    include "data/student.php";
    $students = searchStudents($search_key, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Search Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="student-add.php" class="btn btn-dark">Add New Student</a>
        <form action="student-search.php" class="mt-3 n-table" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="searchKey" placeholder="Search..." value="<?= htmlspecialchars($search_key) ?>">
                <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-3 n-table" role="alert"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-info mt-3 n-table" role="alert"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <?php if ($students): ?>
            <div class="table-responsive">
                <table class="table table-bordered mt-3 n-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $student): ?>
                            <tr>
                                <th><?= $i + 1 ?></th>
                                <td><?= $student['student_id'] ?></td>
                                <td>
                                    <a href="student-view.php?student_id=<?= $student['student_id'] ?>">
                                        <?= htmlspecialchars($student['fname']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($student['lname']) ?></td>
                                <td><?= htmlspecialchars($student['username']) ?></td>
                                <td><?= htmlspecialchars($student['department'] ?? 'No department assigned') ?></td>
                                <td>
                                    <a href="student-edit.php?student_id=<?= $student['student_id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="student-delete.php?student_id=<?= $student['student_id'] ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info w-450 m-5" role="alert">
                No Results Found
                <a href="student.php" class="btn btn-dark">Go Back</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });
    </script>
</body>
</html>
<?php
} else {
    header("Location: student.php");
    exit;
}
?>