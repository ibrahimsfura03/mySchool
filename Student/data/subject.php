<?php

// All Subjects
function getAllSubjects($conn) {
    $sql = "SELECT * FROM subjects";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() >= 1) {
        $subjects = $stmt->fetchAll();
        return $subjects;
    } else {
        return 0;
    }
}

// Get Subjects by ID
function getSubjectById($subject_id, $conn) {
    $sql = "SELECT * FROM subjects WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$subject_id]);

    if ($stmt->rowCount() == 1) {
        $subject = $stmt->fetch();
        return $subject;
    } else {
        return 0;
    }
}

// Get Subjects by Department ID
function getSubjectByDepartment($department_id, $conn) {
    $sql = "SELECT s.subject_id, s.subject, s.subject_code 
            FROM subjects s
            LEFT JOIN departments d ON TRIM(LOWER(s.department)) = TRIM(LOWER(d.department))
            WHERE d.department_id = ? OR d.department_id IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result ?: [];
}
?>