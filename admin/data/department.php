<?php 
// admin/data/department.php

// All Departments
function getAllDepartments($conn){
  $sql = "SELECT department_id, department, faculty FROM departments";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    return []; // Return an empty array if no departments found
  }
}


// Get Department by ID
function getDepartmentById($department_id, $conn){
   // Adjusted to use 'department' and 'faculty' instead of 'department_name'
   $sql = "SELECT department_id, department, faculty FROM departments WHERE department_id = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department_id]);

   if ($stmt->rowCount() == 1) {
     return $stmt->fetch(PDO::FETCH_ASSOC);
   } else {
     return 0;
   }
}

// DELETE
function removeDepartment($department_id, $conn){
   // Adjusted to use 'department_id'
   $sql  = "DELETE FROM departments WHERE department_id = ?";
   $stmt = $conn->prepare($sql);
   return $stmt->execute([$department_id]) ? 1 : 0;
}

// Check if department name exists
function departmentNameExists($department_name, $conn){
   // Adjusted to use 'department' column
   $sql = "SELECT 1 FROM departments WHERE department = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department_name]);
   return $stmt->rowCount() >= 1 ? 1 : 0;
}

// Add new department
function addDepartment($department, $faculty, $conn){
   // Adjusted to use 'department' and 'faculty' columns
   $sql = "INSERT INTO departments (department, faculty) VALUES (?, ?)";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department, $faculty]);
   return $stmt->rowCount() == 1 ? 1 : 0;
}

// Update department
function updateDepartment($department_id, $department, $faculty, $conn){
   // Adjusted to use 'department' and 'faculty' columns
   $sql = "UPDATE departments SET department = ?, faculty = ? WHERE department_id = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$department, $faculty, $department_id]);
   return $stmt->rowCount() == 1 ? 1 : 0;
}
?>
