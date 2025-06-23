<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        if (isset($_POST['course_name']) && isset($_POST['course_code']) && isset($_POST['department']) && isset($_POST['course_id'])) {
            include '../../DB_connection.php';

            $course_name = $_POST['course_name'];
            $course_code = $_POST['course_code'];
            $department = $_POST['department'];
            $course_id = $_POST['course_id'];
            $data = 'course_id=' . urlencode($course_id);

            if (empty($course_id)) {
                $em = "Course ID is required";
                header("Location: ../course-edit.php?error=$em&$data");
                exit;
            } elseif (empty($department)) {
                $em = "Department is required";
                header("Location: ../course-edit.php?error=$em&$data");
                exit;
            } elseif (empty($course_name)) {
                $em = "Course name is required";
                header("Location: ../course-edit.php?error=$em&$data");
                exit;
            } elseif (empty($course_code)) {
                $em = "Course code is required";
                header("Location: ../course-edit.php?error=$em&$data");
                exit;
            } else {
                try {
                    // Check if the course already exists
                    $sql_check = "SELECT * FROM subjects WHERE department=? AND subject_code=?";
                    $stmt_check = $conn->prepare($sql_check);
                    $stmt_check->execute([$department, $course_code]);
                    if ($stmt_check->rowCount() > 0) {
                        $courses = $stmt_check->fetch();
                        if ($courses['subject_id'] == $course_id) {
                            $sql = "UPDATE subjects SET subject=?, subject_code=?, department=? WHERE subject_id=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$course_name, $course_code, $department, $course_id]);
                            $sm = "Course updated successfully";
                            header("Location: ../course-edit.php?success=$sm&$data");
                            exit;
                        } else {
                            $em = "The course already exists";
                            header("Location: ../course-edit.php?error=$em&$data");
                            exit;
                        }
                    } else {
                        $sql = "UPDATE subjects SET subject=?, subject_code=?, department=? WHERE subject_id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$course_name, $course_code, $department, $course_id]);
                        $sm = "Course updated successfully";
                        header("Location: ../course-edit.php?success=$sm&$data");
                        exit;
                    }
                } catch (PDOException $e) {
                    $em = "Database error: " . $e->getMessage();
                    header("Location: ../course-edit.php?error=$em&$data");
                    exit;
                }
            }
        } else {
            $em = "An error occurred";
            header("Location: ../course.php?error=$em");
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