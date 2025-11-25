<?php
session_start();
function requireRole($allowedRoles = []) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: /admin/login.php");
        exit();
    }
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        echo "Access Denied!";
        exit();
    }
}
