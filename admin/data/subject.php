<?php 

// All Subjects
if (!function_exists('getAllSubjects')) {
  function getAllSubjects($conn){
     $sql = "SELECT * FROM subjects";
     $stmt = $conn->prepare($sql);
     $stmt->execute();

     if ($stmt->rowCount() >= 1) {
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
     return 0;
  }
}

// Get Subject by ID
if (!function_exists('getSubjectById')) {
  function getSubjectById($subject_id, $conn){
     $sql = "SELECT * FROM subjects WHERE subject_id=?";
     $stmt = $conn->prepare($sql);
     $stmt->execute([$subject_id]);
     if ($stmt->rowCount() == 1) {
       return $stmt->fetch(PDO::FETCH_ASSOC);
     }
     return 0;
  }
}

// DELETE Subject
if (!function_exists('removeCourse')) {
  function removeCourse($id, $conn){
     $sql = "DELETE FROM subjects WHERE subject_id=?";
     $stmt = $conn->prepare($sql);
     return $stmt->execute([$id]) ? 1 : 0;
  }
}

// Get all subjects assigned to a given lecturer
if (!function_exists('getSubjectsByLecturer')) {
  function getSubjectsByLecturer(int $lecturer_id, PDO $conn): array {
    $sql = "
      SELECT s.* 
        FROM subjects s
        JOIN lecturer_subjects ls ON ls.subject_id = s.subject_id
       WHERE ls.lecturer_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$lecturer_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

?>
