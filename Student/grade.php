<?php
session_start();
if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";
include "data/department.php";
include "data/subject.php";
include "data/score.php"; // For gradeCalc()

$student_id = $_SESSION['student_id'];

// 1) Load student
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    echo "<div class='alert alert-danger'>No such student exists.</div>";
    exit;
}
error_log("STUDENT_GRADE: student_id=$student_id, department_id={$student['department_id']}, level={$student['level']}");

// 2) Load department
$department = getDepartmentById($student['department_id'], $conn);

// 3) Fetch enrolled subjects and scores
try {
    $stmt2 = $conn->prepare(
        "SELECT s.subject_id, s.subject, s.subject_code, sc.first_ca, sc.second_ca, sc.exam
         FROM student_subject_scores sc
         JOIN subjects s ON sc.subject_id = s.subject_id
         WHERE sc.student_id = ?"
    );
    $stmt2->execute([$student_id]);
    $scores = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    error_log("STUDENT_GRADE: scores=" . json_encode($scores));
} catch (PDOException $e) {
    $scores = [];
    $db_error = "Database error: " . $e->getMessage();
    error_log("STUDENT_GRADE_ERROR: student_id=$student_id, error=$db_error");
}

// 4) Compute grand total & CGPA
$grandTotal = 0;
$numSubjects = count($scores);
foreach ($scores as $score) {
    $grandTotal += ($score['first_ca'] + $score['second_ca'] + $score['exam']);
}
if ($numSubjects > 0) {
    $cgpa = round(($grandTotal / ($numSubjects * 100)) * 5.0, 2);
} else {
    $cgpa = 0.00;
}
// Optionally update students.cgpa
try {
    $stmt3 = $conn->prepare("UPDATE students SET cgpa = ? WHERE student_id = ?");
    $stmt3->execute([$cgpa, $student_id]);
    error_log("STUDENT_GRADE: updated_cgpa=$cgpa for student_id=$student_id");
} catch (PDOException $e) {
    error_log("STUDENT_GRADE_CGPA_UPDATE_ERROR: student_id=$student_id, error=" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report Sheet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .report-section {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 40px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ccc;
        }
        .info-row strong {
            width: 30%;
        }
        .info-value {
            text-align: right;
            width: 65%;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
            .container-fluid { padding: 0; }
            .report-section { border: none; padding: 10px; }
            .report-table th, .report-table td { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 p-0 bg-dark">
                <?php include "inc/sidebar.php"; ?>
            </div>
            <div class="col-md-9 col-lg-10 p-4">

                <div class="text-end no-print mb-3">
                    <button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                </div>

                <div class="report-section">
                    <h4 class="text-center mb-4">STUDENT REPORT SHEET</h4>
                    <div class="info-row"><strong>Full Name</strong><div class="info-value"><?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?></div></div>
                    <div class="info-row"><strong>Student ID</strong><div class="info-value"><?= htmlspecialchars($student['student_id']) ?></div></div>
                    <div class="info-row"><strong>Gender</strong><div class="info-value"><?= htmlspecialchars($student['gender'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>Department</strong><div class="info-value"><?= htmlspecialchars($department['department'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>Faculty</strong><div class="info-value"><?= htmlspecialchars($department['faculty'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>Level</strong><div class="info-value"><?= htmlspecialchars($student['level'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>Date of Birth</strong><div class="info-value"><?= htmlspecialchars($student['date_of_birth'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>Email</strong><div class="info-value"><?= htmlspecialchars($student['email_address'] ?? 'N/A') ?></div></div>
                    <div class="info-row"><strong>CGPA</strong><div class="info-value"><?= number_format($cgpa, 2) ?></div></div>
                </div>

                <div class="report-section">
                    <h4 class="mb-4">Courses & Scores</h4>
                    <div class="table-responsive">
                        <table class="report-table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th>First CA</th>
                                    <th>Second CA</th>
                                    <th>Exam</th>
                                    <th>Total</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($scores)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No enrolled courses found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($scores as $i => $score):
                                        $total = $score['first_ca'] + $score['second_ca'] + $score['exam'];
                                        $grade = gradeCalc($total);
                                    ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($score['subject']) ?></td>
                                            <td><?= htmlspecialchars($score['subject_code']) ?></td>
                                            <td><?= $score['first_ca'] ?? 0 ?></td>
                                            <td><?= $score['second_ca'] ?? 0 ?></td>
                                            <td><?= $score['exam'] ?? 0 ?></td>
                                            <td><?= $total ?></td>
                                            <td><?= htmlspecialchars($grade) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($scores)): ?>
                        <div style="margin-top: 20px; padding: 15px; background-color: rgba(0, 123, 255, 0.1); border-left: 5px solid #007bff; border-radius: 5px;" class="d-flex justify-content-between align-items-center">
                            <div><strong>Grand Total:</strong> <?= $grandTotal ?> / <?= $numSubjects * 100 ?></div>
                            <div><strong>CGPA:</strong> <?= number_format($cgpa, 2) ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($db_error)): ?>
                    <div class="alert alert-danger mt-3"><?= htmlspecialchars($db_error) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            document.querySelector("#navLinks li:nth-child(2) a").classList.add('active');
        });
    </script>
</body>
</html>
<?php
?>