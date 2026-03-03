<?php
$conn = mysqli_connect('localhost', 'root', '', 'book_db');
if (!$conn) {
    die('<div style="padding:2rem;color:red;font-family:Poppins,sans-serif;">Database connection failed: ' . mysqli_connect_error() . '</div>');
}
mysqli_set_charset($conn, 'utf8mb4');
?>
