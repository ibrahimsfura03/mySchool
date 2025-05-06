<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        if (isset($_GET['searchKey'])) {

            $search_key = $_GET['searchKey'];
            include "../DB_connection.php";
            include "data/lecturer.php";
            include "data/subject.php";
            include "data/department.php";
            $lecturers = searchLecturers($search_key, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Search Lecturers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
        include "inc/navbar.php";
        if ($lecturers != 0) {
    ?>
    <div class="container mt-5">
        <a href="lecturer-add.php" class="btn btn-dark">Add New Lecturer</a>

        <form action="lecturer-search.php" method="get" class="mt-3 n-table">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="searchKey" value="<?=$search_key?>" placeholder="Search...">
                <button class="btn btn-primary">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>

        <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger mt-3 n-table" role="alert"><?= $_GET['error'] ?></div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-info mt-3 n-table" role="alert"><?= $_GET['success'] ?></div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Department</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($lecturers as $lecturer) { $i++; ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td><?=$lecturer['lecturer_id']?></td>
                        <td><a href="lecturer-view.php?lecturer_id=<?=$lecturer['lecturer_id']?>"><?=$lecturer['fname']?></a></td>
                        <td><?=$lecturer['lname']?></td>
                        <td><?=$lecturer['username']?></td>
                        <td>
                            <?php 
                                $s = '';
                                if (isset($lecturer['subjects']) && !empty($lecturer['subjects'])) {
                                    $subjects = str_split(trim($lecturer['subjects']));
                                    foreach ($subjects as $subject) {
                                        $s_temp = getSubjectById($subject, $conn);
                                        if ($s_temp != 0) 
                                            $s .= $s_temp['subject_code'] . ', ';
                                    }
                                }
                                echo rtrim($s, ', ');
                            ?>
                        </td>
                        <td>
                            <?php 
                                $g = '';
                                if (isset($lecturer['departments']) && !empty($lecturer['departments'])) {
                                    $departments = str_split(trim($lecturer['departments']));
                                    foreach ($departments as $department) {
                                        $g_temp = getDepartmentById($department, $conn);
                                        if ($g_temp != 0) 
                                            $g .= $g_temp['faculty'] . '-' . $g_temp['department'] . ', ';
                                    }
                                }
                                echo rtrim($g, ', ');
                            ?>
                        </td>
                        <td>
                            <a href="lecturer-edit.php?lecturer_id=<?=$lecturer['lecturer_id']?>" class="btn btn-warning">Edit</a>
                            <a href="lecturer-delete.php?lecturer_id=<?=$lecturer['lecturer_id']?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="alert alert-info w-450 m-5" role="alert">
            No Results Found
            <a href="lecturer.php" class="btn btn-dark">Go Back</a>
        </div>
        <?php } ?>
    </div>

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
            header("Location: lecturer.php");
            exit;
        }

    } else {
        header("Location: ../login.php");
        exit;
    } 
} else {
    header("Location: ../login.php");
    exit;
} 
?>
