<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['lecturer_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include_once "../DB_connection.php";
       include_once "data/subject.php";
       include_once "data/department.php";
       include_once "data/class.php";
       include_once "data/lecturer.php";
       
       $subjects = getAllSubjects($conn);
       $departments = getAllDepartments($conn); // 🛠 Fetch departments
       
       $lecturer_id = $_GET['lecturer_id'];
       $lecturer = getLecturerById($lecturer_id, $conn);

       if ($lecturer == 0) {
         header("Location: lecturer.php");
         exit;
       }

       $assignedSubjects = [];
       if (isset($lecturer['subjects']) && !empty($lecturer['subjects'])) {
           $assignedSubjects = explode(",", $lecturer['subjects']);
       }

           // fetch assigned levels
    $assignedLevels = [];
    $lvlStmt = $conn->prepare("SELECT level FROM lecturer_levels WHERE lecturer_id = ?");
    $lvlStmt->execute([$lecturer_id]);
    while ($r = $lvlStmt->fetch(PDO::FETCH_ASSOC)) {
        $assignedLevels[] = $r['level'];
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Edit Lecturer</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    
    <div class="container mt-5">
        <a href="lecturer.php" class="btn btn-dark">Go Back</a>

        <form method="post"
              class="shadow p-3 mt-5 form-w" 
              action="req/lecturer-edit.php">
        <h3>Edit Lecturer</h3><hr>

        <?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
           <?=$_GET['error']?>
          </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
           <?=$_GET['success']?>
          </div>
        <?php } ?>

        <div class="mb-3">
          <label class="form-label">First name</label>
          <input type="text" class="form-control" value="<?=$lecturer['fname']?>" name="fname">
        </div>

        <div class="mb-3">
          <label class="form-label">Last name</label>
          <input type="text" class="form-control" value="<?=$lecturer['lname']?>" name="lname">
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" value="<?=$lecturer['username']?>" name="username">
        </div>

        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" class="form-control" value="<?=$lecturer['address']?>" name="address">
        </div>

        <div class="mb-3">
          <label class="form-label">Employee number</label>
          <input type="text" class="form-control" value="<?=$lecturer['employee_number']?>" name="employee_number">
        </div>

        <div class="mb-3">
          <label class="form-label">Date of birth</label>
          <input type="date" class="form-control" value="<?=$lecturer['date_of_birth']?>" name="date_of_birth">
        </div>

        <div class="mb-3">
          <label class="form-label">Phone number</label>
          <input type="text" class="form-control" value="<?=$lecturer['phone_number']?>" name="phone_number">
        </div>

        <div class="mb-3">
          <label class="form-label">Qualification</label>
          <input type="text" class="form-control" value="<?=$lecturer['qualification']?>" name="qualification">
        </div>

        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="text" class="form-control" value="<?=$lecturer['email_address']?>" name="email_address">
        </div>

        <div class="mb-3">
  <label class="form-label">Levels</label><br>
  <div class="row row-cols-4">
    <div class="col">
      <input type="checkbox" name="levels[]" value="ONE"
             <?= in_array('ONE', $assignedLevels) ? 'checked' : '' ?>>
      ONE
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="TWO"
             <?= in_array('TWO', $assignedLevels) ? 'checked' : '' ?>>
      TWO
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="THREE"
             <?= in_array('THREE', $assignedLevels) ? 'checked' : '' ?>>
      THREE
    </div>
    <div class="col">
      <input type="checkbox" name="levels[]" value="FOUR"
             <?= in_array('FOUR', $assignedLevels) ? 'checked' : '' ?>>
      FOUR
    </div>
  </div>
</div>


        <div class="mb-3">
          <label class="form-label">Gender</label><br>
          <input type="radio" value="Male" <?php if($lecturer['gender'] == 'Male') echo 'checked'; ?> name="gender"> Male
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" value="Female" <?php if($lecturer['gender'] == 'Female') echo 'checked'; ?> name="gender"> Female
        </div>

        <input type="hidden" value="<?=$lecturer['lecturer_id']?>" name="lecturer_id">

        <div class="mb-3">
          <label class="form-label">Subjects</label>
          <div class="row row-cols-5">
            <?php foreach($subjects as $sub): ?>
              <div class="col">
                <input type="checkbox" name="subjects[]" value="<?=$sub['subject_id']?>"
                  <?= in_array($sub['subject_id'], $assignedSubjects) ? 'checked' : '' ?>>
                <?=$sub['subject']?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mb-3">
  <label class="form-label">Department</label>
  <select name="department_id" class="form-select">
    <option value="">Select Department</option>
    <?php foreach($departments as $dep): ?>
      <option value="<?= $dep['department_id'] ?>"
        <?= ($lecturer['department_id'] == $dep['department_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($dep['faculty'] . ' - ' . $dep['department']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>


        <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <form method="post"
              class="shadow p-3 my-5 form-w" 
              action="req/lecturer-change.php"
              id="change_password">
        <h3>Change Password</h3><hr>

          <?php if (isset($_GET['perror'])) { ?>
            <div class="alert alert-danger" role="alert"><?=$_GET['perror']?></div>
          <?php } ?>
          <?php if (isset($_GET['psuccess'])) { ?>
            <div class="alert alert-success" role="alert"><?=$_GET['psuccess']?></div>
          <?php } ?>

        <div class="mb-3">
          <label class="form-label">Admin password</label>
          <input type="password" class="form-control" name="admin_pass"> 
        </div>

        <div class="mb-3">
          <label class="form-label">New password </label>
          <div class="input-group mb-3">
              <input type="text" class="form-control" name="new_pass" id="passInput">
              <button class="btn btn-secondary" id="gBtn">Random</button>
          </div>
        </div>

        <input type="hidden" value="<?=$lecturer['lecturer_id']?>" name="lecturer_id">

        <div class="mb-3">
          <label class="form-label">Confirm new password</label>
          <input type="text" class="form-control" name="c_new_pass" id="passInput2"> 
        </div>

        <button type="submit" class="btn btn-primary">Change</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(2) a").addClass('active');
        });

        function makePass(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
           }
           document.getElementById('passInput').value = result;
           document.getElementById('passInput2').value = result;
        }

        document.getElementById("gBtn").addEventListener("click", function(event) {
            event.preventDefault();
            makePass(8);
        });
    </script>
</body>
</html>
<?php 
    } else {
        header("Location: lecturer.php");
        exit;
    }
} else {
    header("Location: lecturer.php");
    exit;
}
?>
