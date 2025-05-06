<?php 
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../logout.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['course_name']) &&
        isset($_POST['course_code']) &&
        isset($_POST['department'])
    ) {
        include '../../DB_connection.php';

        $course_name = trim($_POST['course_name']);
        $course_code = trim($_POST['course_code']);
        $department  = trim($_POST['department']);

        if ($course_name === '') {
            $em = "Course name is required";
            header("Location: ../course-add.php?error=".urlencode($em));
            exit;
        }
        if ($course_code === '') {
            $em = "Course code is required";
            header("Location: ../course-add.php?error=".urlencode($em));
            exit;
        }
        if ($department === '' || !is_numeric($department)) {
            $em = "Valid department is required";
            header("Location: ../course-add.php?error=".urlencode($em));
            exit;
        }

        // Check for duplicate course code in the same department
        $sql_check = "SELECT 1 FROM subjects WHERE department = ? AND subject_code = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([$department, $course_code]);
        if ($stmt_check->fetchColumn()) {
            $em = "That course code already exists in this department";
            header("Location: ../course-add.php?error=".urlencode($em));
            exit;
        }

        // Insert new course
        $sql = "INSERT INTO subjects (department, subject, subject_code) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$department, $course_name, $course_code]);

        $sm = "New course created successfully";
        header("Location: ../course-add.php?success=".urlencode($sm));
        exit;
    } else {
        $em = "Missing required form data";
        header("Location: ../course-add.php?error=".urlencode($em));
        exit;
    }
} else {
    header("Location: ../course-add.php");
    exit;
}
