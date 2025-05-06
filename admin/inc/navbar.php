<?php
  $currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="../logo.png" width="40">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="navLinks">
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='index.php') echo 'active';?>" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='lecturer.php') echo 'active';?>" href="lecturer.php">Lecturers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='student.php') echo 'active';?>" href="student.php">Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='department.php') echo 'active';?>" href="department.php">Department</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='course.php') echo 'active';?>" href="course.php">Course</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='settings.php') echo 'active';?>" href="settings.php">Settings</a>
        </li>
        <!-- new Admins nav -->
        <li class="nav-item">
          <a class="nav-link <?php if($currentPage=='admins.php') echo 'active';?>" href="admins.php">Admins</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
