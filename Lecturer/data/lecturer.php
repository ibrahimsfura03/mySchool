<?php  
// Get Lecturer by ID
function getLecturerById($lecturer_id, $conn) {
  $sql = "SELECT l.*, 
                 GROUP_CONCAT(DISTINCT ls.subject_id) AS subjects,
                 ANY_VALUE(lc.class_id) AS department_id
          FROM lecturers l
          LEFT JOIN lecturer_subjects ls ON l.lecturer_id = ls.lecturer_id
          LEFT JOIN lecturer_classes lc ON l.lecturer_id = lc.lecturer_id
          WHERE l.lecturer_id = ? 
          GROUP BY l.lecturer_id";
  
  $stmt = $conn->prepare($sql);
  $stmt->execute([$lecturer_id]);

  if ($stmt->rowCount() === 1) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  return 0;
}