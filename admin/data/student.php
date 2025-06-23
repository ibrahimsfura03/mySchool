<?php 

// All Students 
function getAllStudents($conn) {
  $sql = "SELECT 
            s.*,
            d.department AS department,
            s.level
          FROM students s
          LEFT JOIN departments d 
            ON s.department_id = d.department_id
          ORDER BY s.student_id ASC";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    return 0;
  }
}

// DELETE
function removeStudent($id, $conn){
   try {
       // Start transaction
       $conn->beginTransaction();

       // Delete dependent records from student_subject_scores
       $sql_scores = "DELETE FROM student_subject_scores WHERE student_id = ?";
       $stmt_scores = $conn->prepare($sql_scores);
       $stmt_scores->execute([$id]);

       // Delete student from students
       $sql_student = "DELETE FROM students WHERE student_id = ?";
       $stmt_student = $conn->prepare($sql_student);
       $stmt_student->execute([$id]);

       // Commit transaction
       $conn->commit();
       return 1;
   } catch (PDOException $e) {
       // Roll back transaction on error
       $conn->rollBack();
       error_log("DELETE_STUDENT_ERROR: student_id=$id, error=" . $e->getMessage());
       return 0;
   }
}

// Get Student By Id 
function getStudentById($id, $conn){
   $sql = "SELECT s.*, d.department AS department
           FROM students s
           LEFT JOIN departments d ON s.department_id = d.department_id
           WHERE s.student_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() == 1) {
     $student = $stmt->fetch();
     return $student;
   } else {
    return 0;
   }
}

// Check if the username Unique
function unameIsUnique($uname, $conn, $student_id=0){
   $sql = "SELECT username, student_id FROM students
           WHERE username=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$uname]);
   
   if ($student_id == 0) {
     if ($stmt->rowCount() >= 1) {
       return 0;
     } else {
      return 1;
     }
   } else {
    if ($stmt->rowCount() >= 1) {
       $student = $stmt->fetch();
       if ($student['student_id'] == $student_id) {
         return 1;
       } else {
        return 0;
      }
     } else {
      return 1;
     }
   }
}

// Search 
function searchStudents($key, $conn){
   $key = preg_replace('/(?<!\\\)([%_])/', '\\\$1',$key);
   $sql = "SELECT * FROM students
           WHERE student_id LIKE ? 
           OR fname LIKE ?
           OR address LIKE ?
           OR email_address LIKE ?
           OR parent_fname LIKE ?
           OR parent_lname LIKE ?
           OR parent_phone_number LIKE ?
           OR lname LIKE ?
           OR username LIKE ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$key, $key, $key, $key, $key, $key, $key, $key, $key]);

   if ($stmt->rowCount() >= 1) {
     $students = $stmt->fetchAll();
     return $students;
   } else {
    return 0;
   }
}
?>