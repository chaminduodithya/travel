<?php
// Auth guard — include at the top of every protected admin page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // $_SERVER['SCRIPT_NAME'] is the URL path of the calling script, e.g. /travel.-main/admin/dashboard.php
    // dirname gives /travel.-main/admin, so login.php is at /travel.-main/admin/login.php
    $adminPath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    header("Location: " . $adminPath . "/login.php");
    exit();
}
?>
