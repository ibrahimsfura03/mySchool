<?php
    

// admin/data/admin.php

// Fetch all admins
if (!function_exists('getAllAdmins')) {
    function getAllAdmins(PDO $conn): array {
        $stmt = $conn->query("SELECT admin_id, fname, lname, username FROM admin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Fetch one admin by ID
if (!function_exists('getAdminById')) {
    function getAdminById(int $id, PDO $conn) {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: 0;
    }
}

// Check username uniqueness (exclude a given admin_id)
if (!function_exists('adminUnameUnique')) {
    function adminUnameUnique(string $uname, PDO $conn, int $admin_id = 0): bool {
        $stmt = $conn->prepare("SELECT admin_id FROM admin WHERE username = ?");
        $stmt->execute([$uname]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return true;
        }
        return $admin_id && $row['admin_id'] == $admin_id;
    }
}

// Verify an admin’s password
if (!function_exists('adminPasswordVerify')) {
    function adminPasswordVerify(string $plain, PDO $conn, int $admin_id): bool {
        $stmt = $conn->prepare("SELECT password FROM admin WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        return password_verify($plain, $row['password']);
    }
}