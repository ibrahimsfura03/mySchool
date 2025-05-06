<?php
// Student/data/department.php

/**
 * Fetch one department by ID.
 * @param int    $dept_id
 * @param PDO    $conn
 * @return array|int  ['department_id','faculty','department'] or 0 if not found
 */
function getDepartmentById($dept_id, $conn) {
    $sql = "SELECT department_id, faculty, department
            FROM departments
            WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([(int)$dept_id]);
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return 0;
}

/**
 * Fetch all departments (if you ever need them).
 * @param PDO $conn
 * @return array|int  list of departments or 0 if none
 */
function getAllDepartments($conn) {
    $sql = "SELECT department_id, faculty, department
            FROM departments
            ORDER BY faculty, department";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() >= 1) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return 0;
}
