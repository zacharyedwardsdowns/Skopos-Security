<?php

    // Begin a session on login.
    session_start();

    // Create variables for the username and password from the form.
    $username = $_POST['uname'];
    $passwrd = $_POST['pass'];

    // Create connection to the database.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");

    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
    }

    // Check them against the database for a match.
    $sql = "SELECT FROM userdata WHERE username='$username' AND password='$password'";
    $result = mysqli_query($link, $sql);

    // If the login does not match kick the user back to the login page.
    if (mysqli_num_rows($result) == 1)
        {
            return;
        }
        else 
        {
            $url = 'https://skopossecurity.com/login';
            header( "Location: $url" );
            mysqli_close($link);
            exit;
        }

    // https://stackoverflow.com/questions/16932113/passing-multiple-php-variables-to-shell-exec/16932181
    // Not that the user is confimed for login a sessionID is needed.
    // Get a unique sessionID from the sessionGen python script.
    shell_exec("python3 Website/Login/sessionGen.py");

    // Grab the sessionID from the database.
    $sql = "SELECT FROM sessions WHERE username = '$username';";
    $result = mysql_query($link, $sql);
    // Possibly use fetch row to grab?
    // https://www.w3schools.com/php/func_mysqli_fetch_row.asp

    // Make it a $_SESSION variable so that it is accesible accross pages.
    //$_SESSION["sessionID"] = 

    

?>