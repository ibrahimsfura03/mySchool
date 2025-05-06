<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

include '../DB_connection.php';
include 'data/department.php';
$departments = getAllDepartments($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Add Course</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    
    <div class="container mt-5">
        <a href="course.php" class="btn btn-dark">Go Back</a><br><br>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?=$_GET['error']?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?=$_GET['success']?></div>
        <?php endif; ?>

        <?php if (!$departments): ?>
            <div class="alert alert-info" role="alert">First create department.</div>
        <?php else: ?>
        <form method="post" class="shadow p-3 mt-5 form-w" action="req/course-add.php">
            <h3>Add New Course</h3><hr>

            <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Course Code</label>
                <input type="text" name="course_code" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?=$department['department_id']?>">
                            <?=$department['faculty']?> - <?=$department['department']?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(8) a").addClass('active');
        });
    </script>
</body>
</html>
