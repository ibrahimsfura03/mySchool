<?php
// Get Lecturer by ID (with department name)
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



// Get all lecturers
if (!function_exists('getAllLecturers')) {
    function getAllLecturers($conn) {
        $sql  = "SELECT * FROM lecturers";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() >= 1) {
            $lecturers = $stmt->fetchAll();
            return $lecturers;
        }

        return 0;
    }
}

// Check if a username is unique for lecturers
if (!function_exists('unameIsUnique')) {
    function unameIsUnique($uname, $conn, $lecturer_id = 0) {
        $sql = "SELECT COUNT(*) FROM lecturers WHERE username = ? AND lecturer_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname, $lecturer_id]);
    
        $count = $stmt->fetchColumn();
        return $count == 0 ? 1 : 0;
    }
    
}

// Delete a lecturer by ID
if (!function_exists('removeLecturer')) {
    function removeLecturer($lecturer_id, $conn) {
        $sql  = "DELETE FROM lecturers WHERE lecturer_id = ?";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([$lecturer_id]);

        return $result ? 1 : 0;
    }
}

function getLevelsByLecturer(int $lecturer_id, PDO $conn): array {
    $stmt = $conn->prepare(
      "SELECT level 
         FROM lecturer_levels 
        WHERE lecturer_id = ? 
        ORDER BY FIELD(level,'ONE','TWO','THREE','FOUR')"
    );
    $stmt->execute([$lecturer_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


// Search lecturers by various fields
if (!function_exists('searchLecturers')) {
    function searchLecturers($key, $conn) {
        // escape wildcards
        $key = preg_replace('/([%_])/', '\\\\$1', $key);
        $like = "%{$key}%";

        $sql  = "SELECT * FROM lecturers
                 WHERE lecturer_id LIKE ?
                 OR fname LIKE ?
                 OR lname LIKE ?
                 OR username LIKE ?
                 OR employee_number LIKE ?
                 OR phone_number LIKE ?
                 OR qualification LIKE ?
                 OR email_address LIKE ?
                 OR address LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$like, $like, $like, $like, $like, $like, $like, $like, $like]);

        if ($stmt->rowCount() >= 1) {
            $lecturers = $stmt->fetchAll();
            return $lecturers;
        }

        return 0;
    }
}
?>
