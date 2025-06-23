<?php 
session_start();
if (isset($_SESSION['lecturer_id'], $_SESSION['role']) && $_SESSION['role'] === 'Lecturer') {
    include "../DB_connection.php";
    include "data/department.php";
    include "data/lecturer.php";
    
    $lecturer_id = $_SESSION['lecturer_id'];

    // Get levels the lecturer teaches
    $stmt2 = $conn->prepare("SELECT level FROM lecturer_levels WHERE lecturer_id = ?");
    $stmt2->execute([$lecturer_id]);
    $levels = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    error_log("LECTURER_STUDENTS: lecturer_id=$lecturer_id, levels=" . json_encode($levels));

    // Get lecturer's assigned departments
    $stmt4 = $conn->prepare("SELECT class_id FROM lecturer_classes WHERE lecturer_id = ?");
    $stmt4->execute([$lecturer_id]);
    $departments = $stmt4->fetchAll(PDO::FETCH_COLUMN);
    error_log("LECTURER_STUDENTS: departments=" . json_encode($departments));

    // Fallback: Get departments from lecturer's subjects if lecturer_classes is empty
    if (empty($departments)) {
        $stmt5 = $conn->prepare("SELECT DISTINCT sub.department 
                                FROM subjects sub 
                                JOIN lecturer_subjects ls ON sub.subject_id = ls.subject_id 
                                WHERE ls.lecturer_id = ?");
        $stmt5->execute([$lecturer_id]);
        $departments = $stmt5->fetchAll(PDO::FETCH_COLUMN);
        error_log("LECTURER_STUDENTS: departments_from_subjects=" . json_encode($departments));
    }

    // Get lecturer's subjects
    $stmt3 = $conn->prepare("SELECT subject_id FROM lecturer_subjects WHERE lecturer_id = ?");
    $stmt3->execute([$lecturer_id]);
    $subjects = $stmt3->fetchAll(PDO::FETCH_COLUMN);
    error_log("LECTURER_STUDENTS: subjects=" . json_encode($subjects));

    // Log all students in the database
    $stmt_all_db = $conn->prepare("SELECT student_id, fname, lname, level, department_id FROM students");
    $stmt_all_db->execute();
    $all_db_students = $stmt_all_db->fetchAll(PDO::FETCH_ASSOC);
    error_log("LECTURER_STUDENTS: all_database_students=" . json_encode($all_db_students));

    // Log potential students
    $all_students = [];
    if (!empty($levels) && !empty($departments)) {
        $level_placeholders = implode(',', array_fill(0, count($levels), '?'));
        $dept_placeholders = implode(',', array_fill(0, count($departments), '?'));
        $sql_all = "SELECT student_id, fname, lname, level, department_id
                    FROM students
                    WHERE level IN ($level_placeholders)
                    AND department_id IN ($dept_placeholders)";
        $params_all = array_merge($levels, $departments);
        $stmt_all = $conn->prepare($sql_all);
        $stmt_all->execute($params_all);
        $all_students = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
        error_log("LECTURER_STUDENTS: all_potential_students=" . json_encode($all_students));
    }

    // Fetch students
    $students = [];
    $error_message = "No students found in your assigned courses.";
    if (!empty($levels) && !empty($departments)) {
        $level_placeholders = implode(',', array_fill(0, count($levels), '?'));
        $dept_placeholders = implode(',', array_fill(0, count($departments), '?'));

        // Fallback: All students in assigned departments and levels
        $sql_fallback = "SELECT DISTINCT s.student_id, s.fname, s.lname, s.level, s.department_id
                        FROM students s
                        WHERE s.level IN ($level_placeholders)
                        AND s.department_id IN ($dept_placeholders)
                        ORDER BY s.level, s.department_id, s.fname";
        $params = array_merge($levels, $departments);
        error_log("LECTURER_STUDENTS: fallback_query=$sql_fallback, params=" . json_encode($params));
        
        $stmt7 = $conn->prepare($sql_fallback);
        $stmt7->execute($params);
        $students = $stmt7->fetchAll(PDO::FETCH_ASSOC);
        error_log("LECTURER_STUDENTS: fallback_fetched_students=" . json_encode($students));

        // Main query: Enrolled students (only if fallback is empty)
        if (empty($students) && !empty($subjects)) {
            $sql = "SELECT DISTINCT s.student_id, s.fname, s.lname, s.level, s.department_id
                    FROM students s
                    JOIN student_subject_scores sc ON s.student_id = sc.student_id
                    JOIN subjects sub ON sc.subject_id = sub.subject_id
                    JOIN lecturer_subjects ls ON sub.subject_id = ls.subject_id
                    WHERE ls.lecturer_id = ?
                    AND s.level IN ($level_placeholders)
                    AND s.department_id IN ($dept_placeholders)
                    ORDER BY s.level, s.department_id, s.fname";
            $params = array_merge([$lecturer_id], $levels, $departments);
            
            error_log("LECTURER_STUDENTS: query=$sql, params=" . json_encode($params));
            
            $stmt6 = $conn->prepare($sql);
            $stmt6->execute($params);
            $students = $stmt6->fetchAll(PDO::FETCH_ASSOC);
            error_log("LECTURER_STUDENTS: fetched_students=" . json_encode($students));
        }

        if (empty($students)) {
            $error_message = "No students found in your assigned departments and levels. Please check your assignments.";
        }
    } else {
        $error_message = "No levels or departments assigned to you. Please contact the administrator.";
        error_log("LECTURER_STUDENTS: no_levels_or_departments");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lecturer – My Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="../css/style.css" rel="stylesheet"/>
  <link rel="icon" href="../logo.png"/>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark vh-100 sidebar">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 col-lg-10 p-4">
        <h4 class="mb-4">My Students</h4>

        <?php if (count($students) > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Student Name</th>
                  <th>Level</th>
                  <th>Department</th>
                  <th>Student ID</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($students as $i => $stu): 
                      $dept = getDepartmentById((int)$stu['department_id'], $conn);
                      $deptLabel = $dept
                                ? htmlspecialchars($dept['faculty'] . ' - ' . $dept['department'])
                                : 'N/A';
                ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($stu['fname'] . ' ' . $stu['lname']) ?></td>
                    <td><?= htmlspecialchars($stu['level'] ?? 'N/A') ?></td>
                    <td><?= $deptLabel ?></td>
                    <td><?= htmlspecialchars($stu['student_id']) ?></td>
                    <td>
                      <a href="student-view.php?student_id=<?= $stu['student_id'] ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info w-75 mx-auto" role="alert">
            <?= htmlspecialchars($error_message) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelector("#navLinks li:nth-child(3) a").classList.add('active');
  </script>
</body>
</html>
<?php
} else {
    header("Location: ../login.php");
    exit;
}
?>