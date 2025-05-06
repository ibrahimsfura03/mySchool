<?php 
// All Departments
function getAllDepartments($conn){
   $sql = "SELECT * FROM departments";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $departments = $stmt->fetchAll();
     return $departments;
   }else {
    return 0;
   }
}

// Get Department by ID
function getDepartmentById($department_id, $conn){
   $sql = "SELECT * FROM departments
           WHERE department_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department_id]);

   if ($stmt->rowCount() == 1) {
     $department = $stmt->fetch();
     return $department;
   }else {
    return 0;
   }
}

// DELETE
function removeDepartment($id, $conn){
   $sql  = "DELETE FROM departments
           WHERE department_id=?";
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   if ($re) {
     return 1;
   }else {
    return 0;
   }
}
