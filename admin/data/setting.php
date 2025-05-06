<?php 

function getSetting($conn){
  $sql = "SELECT * FROM setting LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() == 1) {
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } else {
    return [
       'school_name' => '',
       'slogan' => '',
       'about' => '',
       'current_year' => '',
       'current_semester' => ''
    ];
  }
}
