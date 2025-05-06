<?php
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../../login.php");
    exit;
}

$required = ['student_id','first_ca','second_ca','exam'];
foreach ($required as $f) {
    if (!isset($_POST[$f]) || $_POST[$f] === '') {
        header("Location: ../edit_student.php?student_id=".$_POST['student_id']."&error=All fields required");
        exit;
    }
}

include '../../DB_connection.php';

$student_id = (int) $_POST['student_id'];
$first_ca   = (int) $_POST['first_ca'];
$second_ca  = (int) $_POST['second_ca'];
$exam       = (int) $_POST['exam'];

$stmt = $conn->prepare("
    UPDATE students
       SET first_ca  = ?,
           second_ca = ?,
           exam      = ?
     WHERE student_id = ?
");
$stmt->execute([$first_ca, $second_ca, $exam, $student_id]);

header("Location: ../students.php?success=Scores updated");
exit;
