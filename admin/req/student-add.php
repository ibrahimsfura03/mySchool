<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['fname']) &&
            isset($_POST['lname']) &&
            isset($_POST['username']) &&
            isset($_POST['pass']) &&
            isset($_POST['address']) &&
            isset($_POST['gender']) &&
            isset($_POST['email_address']) &&
            isset($_POST['date_of_birth']) &&
            isset($_POST['parent_fname']) &&
            isset($_POST['parent_lname']) &&
            isset($_POST['parent_phone_number']) &&
            isset($_POST['department'])) {
            
            include '../../DB_connection.php';
            include "../data/student.php";

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $uname = $_POST['username'];
            $pass = $_POST['pass'];
            $address = $_POST['address'];
            $gender = $_POST['gender'];
            $email_address = $_POST['email_address'];
            $date_of_birth = $_POST['date_of_birth'];
            $parent_fname = $_POST['parent_fname'];
            $parent_lname = $_POST['parent_lname'];
            $parent_phone_number = $_POST['parent_phone_number'];
            $department_id = $_POST['department']; // <-- renamed to match DB column
            $level = isset($_POST['level']) && $_POST['level'] !== '' ? $_POST['level'] : 'ONE';

            $data = "uname=$uname&fname=$fname&lname=$lname&address=$address&gender=$gender&pfn=$parent_fname&pln=$parent_lname&ppn=$parent_phone_number&department=$department_id&level=$level";

            // Validation
            if (empty($fname)) {
                $em = "First name is required";
            } else if (empty($lname)) {
                $em = "Last name is required";
            } else if (empty($uname)) {
                $em = "Username is required";
            } else if (!unameIsUnique($uname, $conn)) {
                $em = "Username is already taken!";
            } else if (empty($pass)) {
                $em = "Password is required";
            } else if (empty($address)) {
                $em = "Address is required";
            } else if (empty($gender)) {
                $em = "Gender is required";
            } else if (empty($email_address)) {
                $em = "Email address is required";
            } else if (empty($date_of_birth)) {
                $em = "Date of birth is required";
            } else if (empty($parent_fname)) {
                $em = "Parent first name is required";
            } else if (empty($parent_lname)) {
                $em = "Parent last name is required";
            } else if (empty($parent_phone_number)) {
                $em = "Parent phone number is required";
            }

            if (isset($em)) {
                header("Location: ../student-add.php?error=$em&$data");
                exit;
            }

            $pass = password_hash($pass, PASSWORD_DEFAULT);

            // Corrected INSERT query using department_id
            $sql = "INSERT INTO students
                      (username, password, fname, lname, department_id, address, gender, email_address, date_of_birth, parent_fname, parent_lname, parent_phone_number, level)
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $uname,
                $pass,
                $fname,
                $lname,
                $department_id,
                $address,
                $gender,
                $email_address,
                $date_of_birth,
                $parent_fname,
                $parent_lname,
                $parent_phone_number,
                $level
            ]);

            $sm = "New student registered successfully";
            header("Location: ../student-add.php?success=$sm");
            exit;

        } else {
            $em = "An error occurred";
            header("Location: ../student-add.php?error=$em");
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
