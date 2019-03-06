<?php
    // Start a session.
    session_start();

    // Get user name.
    $username = $_SESSION["username"];

    // Delete session id from database.
    // Create connection to the database.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");
    
    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
    }

    // Check them against the database for a match.
    $sql = "DELETE FROM sessions WHERE username='$username';";
    mysqli_query($link, $sql);
    
    // Destroys the users session.
    session_destroy();

    // Send them to the login page.
    $url = 'https://skopossecurity.com/login';
    header( "Location: $url" );
?>