<?php
session_start();
include "../db_conn.php";

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    if (isset($_GET['id'])) {
        $admin_id = $_GET['id'];

        // Prevent deleting yourself (optional, but recommended)
        if ($_SESSION['admin_id'] == $admin_id) {
            header("Location: ../admins.php?error=You cannot delete yourself!");
            exit;
        }

        // Delete admin
        $stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
        $stmt->execute([$admin_id]);

        header("Location: ../admins.php?success=Admin deleted successfully");
        exit;
    } else {
        header("Location: ../admins.php?error=Missing admin ID");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
?>
