<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	

if (isset($_POST['faculty']) &&
    isset($_POST['department']) &&
    isset($_POST['department_id'])) {
    
    include '../../DB_connection.php';

    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $department_id = $_POST['department_id'];
   
    $data = 'faculty='.$faculty.'&department='.$department.'&department_id='.$department_id;

    if (empty($faculty)) {
        $em  = "Faculty is required";
        header("Location: ../department-edit.php?error=$em&$data");
        exit;
    }else if (empty($department)) {
        $em  = "Department is required";
        header("Location: ../department-edit.php?error=$em&$data");
        exit;
    }else {
        // Check if the database has been updated to use the new column names
        // If you've run the SQL queries to update the database structure, use this:
        $sql  = "UPDATE grades SET department=?, faculty=? WHERE department_id=?";
        
        // If you haven't updated the database structure, use this instead:
        // $sql  = "UPDATE grades SET grade=?, grade_code=? WHERE grade_id=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$department, $faculty, $department_id]);
        $sm = "Department updated successfully";
        header("Location: ../department-edit.php?success=$sm&$data");
        exit;
	}
    
  }else {
  	$em = "An error occurred";
    header("Location: ../department.php?error=$em");
    exit;
  }

  }else {
    header("Location: ../../logout.php");
    exit;
  } 
}else {
	header("Location: ../../logout.php");
	exit;
}
