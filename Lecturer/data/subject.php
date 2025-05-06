<?php 

// All Subjects
function getAllSubjects($conn){
   $sql = "SELECT * FROM subjects";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $subjects = $stmt->fetchAll();
     return $subjects;
   }else {
   	return 0;
   }
}

// Get Subjects by ID
function getSubjectById($subject_id, $conn){
   $sql = "SELECT * FROM subjects
           WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$subject_id]);

   if ($stmt->rowCount() == 1) {
     $subject = $stmt->fetch();
     return $subject;
   }else {
   	return 0;
   }
}


// Get Subjects by ID
function getSubjectByDepartment($department, $conn){
   $sql = "SELECT * FROM subjects
           WHERE department=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department]);

   if ($stmt->rowCount() > 0) {
     $subject = $stmt->fetchAll();
     return $subject;
   }else {
    return 0;
   }
}


function getSubjectsByLecturer(int $lecturer_id, PDO $conn): array {
  $sql = "
    SELECT s.* 
      FROM subjects s
      JOIN lecturer_subjects ls 
        ON s.subject_id = ls.subject_id
     WHERE ls.lecturer_id = ?
  ";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$lecturer_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 ?>
