<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function check_permission($page_name) {
    check_auth();
    $role = $_SESSION['user_role'];
    $assigned = $_SESSION['assigned_page'];
    
    if ($role === 'Admin') {
        return true; 
    }
    
    if ($role === 'Permission User' && $assigned === $page_name) {
        return true; 
    }
    
    return false; // Normal Users or unauthorized access can only read data
}

function is_admin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin');
}

// Global CSRF Protection Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>