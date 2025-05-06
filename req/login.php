<?php 
session_start();

if (isset($_POST['uname'], $_POST['pass'], $_POST['role'])) {

    include "../DB_connection.php";

    $uname = trim($_POST['uname']);
    $pass  = $_POST['pass'];
    $role  = $_POST['role'];

    // basic validation
    if ($uname === '') {
        header("Location: ../login.php?error=Username is required");
        exit;
    }
    if ($pass === '') {
        header("Location: ../login.php?error=Password is required");
        exit;
    }

    // choose table & session key by role
    switch ($role) {
      case '1':
        $sql      = "SELECT * FROM admin WHERE username = ?";
        $roleName = "Admin";
        $idField  = 'admin_id';
        $redirect = '../admin/index.php';
        break;
      case '2':
        $sql      = "SELECT * FROM lecturers WHERE username = ?";
        $roleName = "Lecturer";
        $idField  = 'lecturer_id';
        $redirect = '../Lecturer/index.php';
        break;
      case '3':
        $sql      = "SELECT * FROM students WHERE username = ?";
        $roleName = "Student";
        $idField  = 'student_id';
        $redirect = '../Student/index.php';
        break;
      default:
        header("Location: ../login.php?error=Invalid role");
        exit;
    }

    // look up user
    $stmt = $conn->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();
        $hash = $user['password'];

        // verify password against hash
        if (password_verify($pass, $hash)) {
            // login success
            $_SESSION['role'] = $roleName;
            $_SESSION[$idField] = $user[$idField];
            header("Location: $redirect");
            exit;
        }
    }

    // if we reach here, login failed
    header("Location: ../login.php?error=Incorrect Username or Password");
    exit;

} else {
    header("Location: ../login.php");
    exit;
}
