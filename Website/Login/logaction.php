<?php
//
// Check login data against database then generate a session ID.
//
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
    $sql = "SELECT username FROM userdata WHERE username='$username' AND usrpass='$passwrd';";
    $result = mysqli_query($link, $sql);

    // If the login does not match kick the user back to the login page.
    if (mysqli_num_rows($result) != 1)
        {
            $url = 'https://skopossecurity.com/login';
            header( "Location: $url" );
            mysqli_close($link);
            exit;
        }

    // If login is successful call python to generate a session id.
    exec("python3 sessionGen.py $username");

    // Grab the sessionID from the database.
    $sql = "SELECT sessionID FROM sessions WHERE username = '$username';";
    $result = mysqli_query($link, $sql);
    $sessID = mysqli_fetch_row($result);
    
    // Make it a $_SESSION variable so that it is accesible accross pages.
    $_SESSION["sessionID"] = $sessID[0];
    $_SESSION["username"] = $username;

//
// Send user to their userpage.
//
    $url = 'https://skopossecurity.com/user/home';
    header( "Location: $url" );
?>