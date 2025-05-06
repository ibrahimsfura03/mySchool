<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    if (isset($_POST['school_name'], $_POST['slogan'], $_POST['about'], $_POST['current_year'], $_POST['current_semester'])) {

        include '../../DB_connection.php';

        $school_name = trim($_POST['school_name']);
        $slogan = trim($_POST['slogan']);
        $about = trim($_POST['about']);
        $current_year = trim($_POST['current_year']);
        $current_semester = trim($_POST['current_semester']);

        // Validate
        if (empty($school_name) || empty($slogan) || empty($about) || empty($current_year) || empty($current_semester)) {
            $em = "All fields are required!";
            header("Location: ../settings.php?error=$em");
            exit;
        }

        // Check if a setting already exists
        $stmt = $conn->prepare("SELECT id FROM setting LIMIT 1");
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // Insert new settings
            $sql = "INSERT INTO setting (school_name, slogan, about, current_year, current_semester)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$school_name, $slogan, $about, $current_year, $current_semester]);
        } else {
            // Update existing settings
            $sql = "UPDATE setting SET 
                        school_name = ?, 
                        slogan = ?, 
                        about = ?, 
                        current_year = ?, 
                        current_semester = ?
                    WHERE id = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$school_name, $slogan, $about, $current_year, $current_semester]);
        }

        $sm = "Settings saved successfully!";
        header("Location: ../settings.php?success=$sm");
        exit;

    } else {
        $em = "Invalid request";
        header("Location: ../settings.php?error=$em");
        exit;
    }

} else {
    header("Location: ../../logout.php");
    exit;
}
