<?php
// admin/data/class.php

// All classes with department & faculty
if (!function_exists('getAllClasses')) {
  function getAllClasses($conn){
    $sql = "
      SELECT 
        c.class_id,
        d.faculty,
        d.department
      FROM class c
      JOIN departments d 
        ON c.department = d.department_id
      ORDER BY c.class_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
  }
}

// Get class by ID
if (!function_exists('getClassById')) {
  function getClassById($class_id, $conn){
     $sql = "SELECT * FROM class WHERE class_id=?";
     $stmt = $conn->prepare($sql);
     $stmt->execute([$class_id]);
     return $stmt->rowCount()==1 ? $stmt->fetch(PDO::FETCH_ASSOC) : 0;
  }
}

// DELETE class
if (!function_exists('removeClass')) {
  function removeClass($id, $conn){
     $sql = "DELETE FROM class WHERE class_id=?";
     $stmt = $conn->prepare($sql);
     return $stmt->execute([$id]) ? 1 : 0;
  }
}


/**
 * data/class.php
 * Return an array of departments assigned to this lecturer.
 */
function getClassesByLecturer($lecturer_id, $conn) {
    $sql = "
      SELECT d.department_id
           , d.faculty
           , d.department
      FROM lecturer_classes lc
      JOIN departments d
        ON lc.class_id = d.department_id
      WHERE lc.lecturer_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$lecturer_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
