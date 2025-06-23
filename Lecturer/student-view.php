<?php
session_start();
if (!isset($_SESSION['lecturer_id'], $_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}
if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    header("Location: students.php");
    exit;
}

include "../DB_connection.php";
include "data/department.php";
include "data/subject.php";

$lecturer_id = (int)$_SESSION['lecturer_id'];
$student_id = (int)$_GET['student_id'];

// 1) Load student
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    exit;
}
error_log("STUDENT_VIEW: lecturer_id=$lecturer_id, student_id=$student_id, student_department_id={$student['department_id']}, student_level={$student['level']}");

// 2) Load subjects taught by this lecturer for the student's department and level
$subjects = [];
$error_message = "No courses found for this student in your assigned department and level.";
$stmt = $conn->prepare(
    "SELECT s.subject_id, s.subject, s.subject_code
     FROM subjects s
     JOIN lecturer_subjects ls ON s.subject_id = ls.subject_id
     JOIN student_subject_scores sc ON s.subject_id = sc.subject_id
     JOIN lecturer_levels ll ON ll.lecturer_id = ls.lecturer_id
     WHERE ls.lecturer_id = ?
     AND sc.student_id = ?
     AND s.department = ?
     AND ll.level = ?"
);
$stmt->execute([$lecturer_id, $student_id, $student['department_id'], $student['level']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!is_array($subjects)) {
    $subjects = [];
}
error_log("STUDENT_VIEW: main_query_subjects=" . json_encode($subjects));

// Fallback: If no subjects, try fetching subjects by department and level without student_subject_scores
if (empty($subjects)) {
    $stmt = $conn->prepare(
        "SELECT s.subject_id, s.subject, s.subject_code
         FROM subjects s
         JOIN lecturer_subjects ls ON s.subject_id = ls.subject_id
         JOIN lecturer_levels ll ON ll.lecturer_id = ls.lecturer_id
         WHERE ls.lecturer_id = ?
         AND s.department = ?
         AND ll.level = ?"
    );
    $stmt->execute([$lecturer_id, $student['department_id'], $student['level']]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!is_array($subjects)) {
        $subjects = [];
    }
    $error_message = "No enrollment records found for this student in your assigned courses.";
    error_log("STUDENT_VIEW: fallback_query_subjects=" . json_encode($subjects));
}

// 3) Fetch scores for this student for lecturer's subjects
$stmt2 = $conn->prepare(
    "SELECT subject_id, first_ca, second_ca, exam 
     FROM student_subject_scores 
     WHERE student_id = ? 
     AND subject_id IN (
         SELECT s.subject_id 
         FROM subjects s
         JOIN lecturer_subjects ls ON s.subject_id = ls.subject_id
         JOIN lecturer_levels ll ON ll.lecturer_id = ls.lecturer_id
         WHERE ls.lecturer_id = ?
         AND s.department = ?
         AND ll.level = ?
     )"
);
$stmt2->execute([$student_id, $lecturer_id, $student['department_id'], $student['level']]);
$scoresMap = [];
while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $scoresMap[$r['subject_id']] = $r;
}
error_log("STUDENT_VIEW: scoresMap=" . json_encode($scoresMap));

// 4) Compute grand total & CGPA for lecturer's subjects
$grandTotal = 0;
$numSubjects = count($subjects);
foreach ($subjects as $sub) {
    $sc = $scoresMap[$sub['subject_id']] ?? ['first_ca' => 0, 'second_ca' => 0, 'exam' => 0];
    $grandTotal += ($sc['first_ca'] + $sc['second_ca'] + $sc['exam']);
}
// Avoid division by zero
if ($numSubjects > 0) {
    $cgpa = round(($grandTotal / ($numSubjects * 100)) * 5.0, 2);
} else {
    $cgpa = 0.00;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student View – <?= htmlspecialchars($student['fname'] . " " . $student['lname']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <h3 class="mb-4">Student Details</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($student['fname'] . " " . $student['lname']) ?></p>
        <p><strong>Level:</strong> <?= htmlspecialchars($student['level'] ?? 'N/A') ?></p>
        <p><strong>Department:</strong> 
            <?= htmlspecialchars(($d = getDepartmentById($student['department_id'], $conn)) ? $d['faculty'] . " - " . $d['department'] : 'N/A') ?>
        </p>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-outline-primary" onclick="window.print()">🖨️ Print Report</button>
        </div>

        <h4 class="mt-4">Courses & Scores</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Code</th>
                        <th>First CA</th>
                        <th>Second CA</th>
                        <th>Exam</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($subjects)): ?>
                        <tr>
                            <td colspan="8" class="text-center"><?= htmlspecialchars($error_message) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subjects as $i => $sub):
                            $sc = $scoresMap[$sub['subject_id']] ?? ['first_ca' => 0, 'second_ca' => 0, 'exam' => 0];
                            $total = $sc['first_ca'] + $sc['second_ca'] + $sc['exam'];
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($sub['subject']) ?></td>
                                <td><?= htmlspecialchars($sub['subject_code']) ?></td>
                                <td><?= $sc['first_ca'] ?></td>
                                <td><?= $sc['second_ca'] ?></td>
                                <td><?= $sc['exam'] ?></td>
                                <td><?= $total ?></td>
                                <td>
                                    <a href="student-grade.php?student_id=<?= $student_id ?>&subject_id=<?= $sub['subject_id'] ?>"
                                       class="btn btn-sm btn-primary">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Box Below Table -->
        <div style="margin-top: 20px; padding: 15px; background-color: rgba(0, 123, 255, 0.1); border-left: 5px solid #007bff; border-radius: 5px;" class="d-flex justify-content-between align-items-center">
            <div><strong>Grand Total:</strong> <?= $grandTotal ?> / <?= $numSubjects * 100 ?></div>
            <div><strong>CGPA:</strong> <?= number_format($cgpa, 2) ?></div>
        </div>
    </div>
</body>
</html>