<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['department_id'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../../DB_connection.php";
     include "../data/department.php";

     $id = $_GET['department_id'];
     
     if (removeDepartment($id, $conn)) {
     	$sm = "Successfully deleted!";
        header("Location: ../department.php?success=$sm");
        exit;
     }else {
        $em = "Unknown error occurred";
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
