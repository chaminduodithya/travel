<?php
    $connection = mysqli_connect('localhost', 'root', '', 'book_db');

    if (!$connection) {
        die('Database connection failed: ' . mysqli_connect_error());
    }

    if (isset($_POST['Send'])) {

        // Escape every value to prevent SQL errors when fields contain
        // single quotes (e.g. "O'Brien", "Conner-Cormier Mills") and
        // to protect against SQL injection.
        $name     = mysqli_real_escape_string($connection, trim($_POST['name']     ?? ''));
        $email    = mysqli_real_escape_string($connection, trim($_POST['email']    ?? ''));
        $phone    = mysqli_real_escape_string($connection, trim($_POST['number']   ?? ''));
        $address  = mysqli_real_escape_string($connection, trim($_POST['address']  ?? ''));
        $location = mysqli_real_escape_string($connection, trim($_POST['location'] ?? ''));
        $guests   = mysqli_real_escape_string($connection, trim($_POST['guests']   ?? ''));
        $arrivals = mysqli_real_escape_string($connection, trim($_POST['arrival']  ?? ''));
        $leaving  = mysqli_real_escape_string($connection, trim($_POST['leaving']  ?? ''));

        $request = "INSERT INTO book_form
                        (name, email, phone, address, location, guests, arrivals, leaving)
                    VALUES
                        ('$name','$email','$phone','$address','$location','$guests','$arrivals','$leaving')";

        $result = mysqli_query($connection, $request);

        if ($result) {
            header("Location: book.php");
            exit();
        } else {
            // Show a user-friendly error instead of a fatal crash
            echo '<p style="font-family:Poppins,sans-serif;color:red;padding:2rem;">
                    Booking failed. Please go back and try again.
                  </p>';
        }

    } else {
        header("Location: book.php");
        exit();
    }
?>