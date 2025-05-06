<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        if (isset($_POST['faculty']) && isset($_POST['department'])) {
            include '../../DB_connection.php';

            $faculty    = trim($_POST['faculty']);
            $department = trim($_POST['department']);
            $data       = 'faculty=' . urlencode($faculty) . '&department=' . urlencode($department);

            if ($faculty === '') {
                $em = "Faculty is required";
                header("Location: ../department-add.php?error=$em&$data");
                exit;
            } elseif ($department === '') {
                $em = "Department is required";
                header("Location: ../department-add.php?error=$em&$data");
                exit;
            }

            // Insert into the real departments table now that department_id is AUTO_INCREMENT
            $sql  = "INSERT INTO departments (faculty, department) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$faculty, $department]);

            $sm = "New Department created successfully";
            header("Location: ../department-add.php?success=$sm");
            exit;
        }

        // Missing POST fields
        $em = "An error occurred";
        header("Location: ../department-add.php?error=$em");
        exit;
    }

    // Not admin
    header("Location: ../../logout.php");
    exit;
}

// Not logged in
header("Location: ../../logout.php");
exit;
