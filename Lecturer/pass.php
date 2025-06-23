<?php 
session_start();
if (isset($_SESSION['lecturer_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Lecturer') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lecturer - Change Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 p-0 bg-dark vh-100 sidebar">
        <?php include "inc/sidebar.php"; ?>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 col-lg-10 p-4">
        <div class="d-flex justify-content-center align-items-center flex-column">
          <form method="post"
                class="shadow p-4 my-5 form-w w-100" 
                style="max-width: 500px;"
                action="req/lecturer-change.php"
                id="change_password">
            <h3 class="text-center">Change Password</h3><hr>

            <?php if (isset($_GET['perror'])) { ?>
              <div class="alert alert-danger" role="alert">
                <?= $_GET['perror'] ?>
              </div>
            <?php } ?>
            <?php if (isset($_GET['psuccess'])) { ?>
              <div class="alert alert-success" role="alert">
                <?= $_GET['psuccess'] ?>
              </div>
            <?php } ?>

            <div class="mb-3">
              <label class="form-label">Old password</label>
              <input type="password" 
                     class="form-control"
                     name="old_pass"> 
            </div>

            <div class="mb-3">
              <label class="form-label">New password</label>
              <div class="input-group">
                <input type="text" 
                       class="form-control"
                       name="new_pass"
                       id="passInput">
                <button class="btn btn-secondary"
                        id="gBtn">
                  Random
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm new password</label>
              <input type="text" 
                     class="form-control"
                     name="c_new_pass"
                     id="passInput2"> 
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Change</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
  <script>
    $(document).ready(function() {
      $("#navLinks li:nth-child(5) a").addClass('active');
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

    document.getElementById('gBtn').addEventListener('click', function(e) {
      e.preventDefault();
      makePass(4);
    });
  </script>
</body>
</html>
<?php 
    } else {
      header("Location: ../login.php");
      exit;
    } 
} else {
  header("Location: ../login.php");
  exit;
} 
?>
