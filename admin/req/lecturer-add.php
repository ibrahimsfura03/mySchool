<?php
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit;
}

$required = [
    'fname','lname','username','pass',
    'address','employee_number','phone_number',
    'qualification','email_address','gender',
    'date_of_birth','subjects','department_id'
];

foreach ($required as $f) {
    if (empty($_POST[$f])) {
        header("Location: ../lecturer-add.php?error=" . urlencode("All fields are required."));
        exit;
    }
}

include '../../DB_connection.php';

$fname         = trim($_POST['fname']);
$lname         = trim($_POST['lname']);
$username      = trim($_POST['username']);
$password      = $_POST['pass'];
$address       = trim($_POST['address']);
$empNum        = trim($_POST['employee_number']);
$phone         = trim($_POST['phone_number']);
$qual          = trim($_POST['qualification']);
$email         = trim($_POST['email_address']);
$gender        = $_POST['gender'];
$dob           = $_POST['date_of_birth'];
$subjects      = $_POST['subjects'];
$department_id = $_POST['department_id'];
$levels = $_POST['levels'] ?? [];

$stmt = $conn->prepare("SELECT COUNT(*) FROM lecturers WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetchColumn() > 0) {
    header("Location: ../lecturer-add.php?error=" . urlencode("Username already exists."));
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO lecturers
          (fname,lname,username,password,address,employee_number,
           phone_number,qualification,email_address,gender,date_of_birth)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    $fname, $lname, $username, $hash, $address, $empNum,
    $phone, $qual, $email, $gender, $dob
]);

$lecturer_id = $conn->lastInsertId();

// assign subjects
$ins = $conn->prepare("INSERT INTO lecturer_subjects (lecturer_id,subject_id) VALUES (?,?)");
foreach ($subjects as $sid) {
    $ins->execute([$lecturer_id, $sid]);
}

// assign department instead of class
$ins2 = $conn->prepare(
    "INSERT INTO lecturer_classes (lecturer_id,class_id) VALUES (?,?)"
  );
  $ins2->execute([$lecturer_id, $department_id]);
  
// assign levels (ONE, TWO, etc.)
if (!empty($levels)) {
    $ins3 = $conn->prepare(
      "INSERT INTO lecturer_levels (lecturer_id, level) VALUES (?, ?)"
    );
    foreach ($levels as $lvl) {
        $ins3->execute([$lecturer_id, $lvl]);
    }
}

  
header("Location: ../lecturer-add.php?success=" . urlencode("Lecturer added successfully!"));
exit;
