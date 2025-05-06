<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
  if ($_SESSION['role'] == 'Admin') {

    // Update student information
    if (
      isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['username']) &&
      isset($_POST['student_id']) && isset($_POST['address']) && isset($_POST['email_address']) &&
      isset($_POST['gender']) && isset($_POST['date_of_birth']) && isset($_POST['parent_fname']) &&
      isset($_POST['parent_lname']) && isset($_POST['parent_phone_number']) && isset($_POST['department_id']) &&
      isset($_POST['level'])
    ) {

      include '../../DB_connection.php';
      include "../data/student.php"; // Make sure this includes all functions you need

      $fname = trim($_POST['fname']);
      $lname = trim($_POST['lname']);
      $uname = trim($_POST['username']);
      $address = trim($_POST['address']);
      $gender = $_POST['gender'];
      $email_address = trim($_POST['email_address']);
      $date_of_birth = $_POST['date_of_birth'];
      $parent_fname = trim($_POST['parent_fname']);
      $parent_lname = trim($_POST['parent_lname']);
      $parent_phone_number = trim($_POST['parent_phone_number']);
      $student_id = $_POST['student_id'];
      $department_id = $_POST['department_id']; // department_id instead of department
      $level = $_POST['level'];  // Using level field

      $data = 'student_id=' . $student_id;

      // Check if any of the fields are empty
      if (
        empty($fname) || empty($lname) || empty($uname) || empty($address) ||
        empty($gender) || empty($email_address) || empty($date_of_birth) ||
        empty($parent_fname) || empty($parent_lname) || empty($parent_phone_number) ||
        empty($department_id) || empty($level)
      ) {
        $em = "All fields are required";
        header("Location: ../student-edit.php?error=$em&$data");
        exit;
      } else if (!unameIsUnique($uname, $conn, $student_id)) {
        $em = "Username is taken! Try another.";
        header("Location: ../student-edit.php?error=$em&$data");
        exit;
      } else {
        // SQL query to update the student data
        $sql = "UPDATE students SET
                  username = ?, fname = ?, lname = ?, department_id = ?, address = ?, gender = ?, 
                  email_address = ?, date_of_birth = ?, parent_fname = ?, parent_lname = ?, 
                  parent_phone_number = ?, level = ?
                WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
          $uname, $fname, $lname, $department_id, $address, $gender,
          $email_address, $date_of_birth, $parent_fname, $parent_lname,
          $parent_phone_number, $level, $student_id
        ]);

        $sm = "Successfully updated!";
        header("Location: ../student-edit.php?success=$sm&$data");
        exit;
      }

    } // Update student password
    else if (
      isset($_POST['admin_pass']) &&
      isset($_POST['new_pass']) &&
      isset($_POST['c_new_pass']) &&
      isset($_POST['student_id'])
    ) {
      include '../../DB_connection.php';

      $admin_pass = $_POST['admin_pass'];
      $new_pass = $_POST['new_pass'];
      $c_new_pass = $_POST['c_new_pass'];
      $student_id = $_POST['student_id'];

      $data = 'student_id=' . $student_id;

      // Check if password fields are empty or do not match
      if (empty($admin_pass)) {
        $em = "Admin password is required";
        header("Location: ../student-edit.php?perror=$em&$data");
        exit;
      } else if (empty($new_pass)) {
        $em = "New password is required";
        header("Location: ../student-edit.php?perror=$em&$data");
        exit;
      } else if (empty($c_new_pass)) {
        $em = "Confirm new password is required";
        header("Location: ../student-edit.php?perror=$em&$data");
        exit;
      } else if ($new_pass !== $c_new_pass) {
        $em = "Passwords do not match";
        header("Location: ../student-edit.php?perror=$em&$data");
        exit;
      } else {
        $admin_id = $_SESSION['admin_id'];
        $sql = "SELECT * FROM admin WHERE admin_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$admin_id]);

        // Check if the admin password is correct
        if ($stmt->rowCount() == 1) {
          $admin = $stmt->fetch();
          if (password_verify($admin_pass, $admin['password'])) {
            $new_pass_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $sql = "UPDATE students SET password = ? WHERE student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$new_pass_hashed, $student_id]);

            $sm = "Password changed successfully!";
            header("Location: ../student-edit.php?psuccess=$sm&$data");
            exit;
          } else {
            $em = "Incorrect admin password";
            header("Location: ../student-edit.php?perror=$em&$data");
            exit;
          }
        } else {
          $em = "Admin not found";
          header("Location: ../student-edit.php?perror=$em&$data");
          exit;
        }
      }

    } else {
      $em = "An error occurred";
      header("Location: ../student.php?error=$em");
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
