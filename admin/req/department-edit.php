<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        if (isset($_POST['faculty']) && isset($_POST['department']) && isset($_POST['department_id'])) {
            include '../../DB_connection.php';

            $faculty = $_POST['faculty'];
            $department = $_POST['department'];
            $department_id = $_POST['department_id'];
            $data = 'faculty=' . urlencode($faculty) . '&department=' . urlencode($department) . '&department_id=' . $department_id;

            if (empty($faculty)) {
                $em = "Faculty is required";
                header("Location: ../department-edit.php?error=$em&$data");
                exit;
            } elseif (empty($department)) {
                $em = "Department is required";
                header("Location: ../department-edit.php?error=$em&$data");
                exit;
            } else {
                try {
                    $sql = "UPDATE departments SET department=?, faculty=? WHERE department_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$department, $faculty, $department_id]);
                    $sm = "Department updated successfully";
                    header("Location: ../department-edit.php?success=$sm&$data");
                    exit;
                } catch (PDOException $e) {
                    $em = "Database error: " . $e->getMessage();
                    header("Location: ../department-edit.php?error=$em&$data");
                    exit;
                }
            }
        } else {
            $em = "An error occurred";
            header("Location: ../department.php?error=$em");
            exit;
        }
    } else {
        header("Location: ../../logout.php");
        exit;
    }
} else {
    header("Location: ../../logout.php");
    exit;
}
?>