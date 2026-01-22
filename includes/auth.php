<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Require login (any role)
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /SaharaProject/login.php");
        exit();
    }
}

// Require admin role
function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        echo "Access denied!";
        exit();
    }
}

// Require restaurant role
function requireRestaurant() {
    requireLogin();
    if ($_SESSION['role'] !== 'restaurant') {
        echo "Access denied!";
        exit();
    }
}

// Require charity role
function requireCharity() {
    requireLogin();
    if ($_SESSION['role'] !== 'charity') {
        echo "Access denied!";
        exit();
    }
}
?>
