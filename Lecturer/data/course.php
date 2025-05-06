<?php
/**
 * Fetch all courses belonging to a given department.
 */
function getCoursesByDepartment(int $deptId, PDO $conn): array {
    $stmt = $conn->prepare("
        SELECT course_id, course_name, course_code, department
          FROM courses
         WHERE department = ?
         ORDER BY course_code
    ");
    $stmt->execute([$deptId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
