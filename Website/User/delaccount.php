<?php
    // Get their account name.
    $username = $_POST["username"];
    
    // Attempt MySQL server connection.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");

    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
    }

    // Delete sessionID from database.
    $sql = "DELETE FROM sessions WHERE username='$username';";
    mysqli_query($link, $sql);

    // Destroys the users session.
    session_destroy();

    // Delete user schedules from database.
    $sql = "DELETE FROM schedule WHERE username='$username';";
    mysqli_query($link, $sql);

    // Delete user account from database.
    $sql = "DELETE FROM userdata WHERE username='$username';";
    mysqli_query($link, $sql);

    // Remove their user directory.
    shell_exec("rm -r /srv/http/ftpserver/$username");

    // Close mysql connection.
    mysqli_close($link);

    // Return to the login page.
    $url = 'https://skopossecurity.com/login';
    header( "Location: $url" );

?>