<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	

if (isset($_POST['department']) &&
    isset($_POST['section'])) {
    
    include '../../DB_connection.php';

    $section = $_POST['section'];
    $department = $_POST['department'];

  if (empty($section)) {
		$em  = "Section is required";
		header("Location: ../class-add.php?error=$em");
		exit;
	}else if (empty($department)) {
		$em  = "Department is required";
		header("Location: ../class-add.php?error=$em");
		exit;
	}else {
        // check if the class already exists
        $sql_check = "SELECT * FROM class 
                      WHERE grade=? AND section=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([$department, $section]);
        if ($stmt_check->rowCount() > 0) {
           $em  = "The class already exists";
           header("Location: ../class-add.php?error=$em");
           exit;
        }else {
          $sql  = "INSERT INTO
                 class(grade, section)
                 VALUES(?,?)";
          $stmt = $conn->prepare($sql);
          $stmt->execute([$department, $section]);
          $sm = "New class created successfully";
          header("Location: ../class-add.php?success=$sm");
          exit;
        } 
	}
    
  }else {
  	$em = "An error occurred";
    header("Location: ../class-add.php?error=$em");
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
