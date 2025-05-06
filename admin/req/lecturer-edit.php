<?php
session_start();
if (!isset($_SESSION['admin_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit;
}

// required fields (we leave Levels out so it’s optional)
$required = [
    'lecturer_id',
    'fname','lname','username',
    'address','employee_number','phone_number',
    'qualification','email_address','gender',
    'date_of_birth','subjects','department_id'
];
foreach ($required as $f) {
    if (empty($_POST[$f])) {
        $lid = $_POST['lecturer_id'] ?? '';
        header("Location: ../lecturer-edit.php?lecturer_id={$lid}&error=" 
               . urlencode("All fields are required."));
        exit;
    }
}

include '../../DB_connection.php';

// pull in and trim
$lecturer_id   = (int) $_POST['lecturer_id'];
$fname         = trim($_POST['fname']);
$lname         = trim($_POST['lname']);
$username      = trim($_POST['username']);
$address       = trim($_POST['address']);
$emp           = trim($_POST['employee_number']);
$phone         = trim($_POST['phone_number']);
$qual          = trim($_POST['qualification']);
$email         = trim($_POST['email_address']);
$gender        = $_POST['gender'];
$dob           = $_POST['date_of_birth'];
$subjects      = $_POST['subjects'];          // array of subject_id
$department_id = (int) $_POST['department_id'];

// <<< NEW: grab the posted levels (or empty array) >>>
$levels = $_POST['levels'] ?? [];

// check username uniqueness (exclude self)
$sql = "SELECT COUNT(*) FROM lecturers WHERE username = ? AND lecturer_id <> ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $lecturer_id]);
if ($stmt->fetchColumn() > 0) {
    header("Location: ../lecturer-edit.php?lecturer_id={$lecturer_id}&error=" 
           . urlencode("Username already taken."));
    exit;
}

// update main lecturer record
$sql = "
    UPDATE lecturers
       SET fname            = ?,
           lname            = ?,
           username         = ?,
           address          = ?,
           employee_number  = ?,
           phone_number     = ?,
           qualification    = ?,
           email_address    = ?,
           gender           = ?,
           date_of_birth    = ?
     WHERE lecturer_id     = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([
    $fname, $lname, $username,
    $address, $emp, $phone,
    $qual, $email, $gender,
    $dob, $lecturer_id
]);

// refresh subject assignments
$conn->prepare("DELETE FROM lecturer_subjects WHERE lecturer_id = ?")
     ->execute([$lecturer_id]);
$insSub = $conn->prepare(
    "INSERT INTO lecturer_subjects (lecturer_id, subject_id) VALUES (?, ?)"
);
foreach ($subjects as $sid) {
    $insSub->execute([$lecturer_id, (int)$sid]);
}

// refresh department assignment
$conn->prepare("DELETE FROM lecturer_classes WHERE lecturer_id = ?")
     ->execute([$lecturer_id]);
$insCls = $conn->prepare(
    "INSERT INTO lecturer_classes (lecturer_id, class_id) VALUES (?, ?)"
);
$insCls->execute([$lecturer_id, $department_id]);

// <<< DEBUG LOGGING >>>
error_log("LECTURER-EDIT: lecturer_id={$lecturer_id}, levels=" . json_encode($levels));

// refresh levels assignments
$conn->prepare("DELETE FROM lecturer_levels WHERE lecturer_id = ?")
     ->execute([$lecturer_id]);

if (!empty($levels)) {
    $insLvl = $conn->prepare(
      "INSERT INTO lecturer_levels (lecturer_id, level) VALUES (?, ?)"
    );
    foreach ($levels as $lvl) {
        $insLvl->execute([$lecturer_id, $lvl]);
    }
}

header("Location: ../lecturer-edit.php?lecturer_id={$lecturer_id}&success=" 
       . urlencode("Lecturer updated successfully."));
exit;
