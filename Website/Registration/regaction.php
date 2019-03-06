<?php
//
// Tests that the captcha is correct. (Comnpromised, but that's okay for now.)
//
    // Defines the variable captcha.
    $captcha;

    // Checks for a captcha response and stores results in captcha.
    if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];

    // If captcha was not filled out, redirect user to the registration page.
    if(!$captcha)
    {
        $url = 'https://skopossecurity.com/register';
        header( "Location: $url" );
        exit;
    }

    // If there is a captcha check if the response was valid.
    $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ld0eY0UAAAAANKl-ICEf7Cf_nJA0bmtzzh_tU62&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

    // If captcha is valid redirect to the userpage, otherwise tell them robots to get off your lawn!
    if($response['success'] == false)
    {
        echo '<h2>Damn robots! Get off my webpage!</h2>';
        exit;
    }

//
// Tests for database duplicates.
//
    // Attempt MySQL server connection.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");

    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
    }

    // Stores post data into variables.
    $email =  $_POST['em'];
    $username = $_POST['uname'];
    $password = $_POST['pass'];
    $phonenumber = $_POST['phnnum'];

    // Check if the password matches, if not then return to the registration page.
    if($password !== $_POST['passcheck'])
    {
        $url = 'https://skopossecurity.com/register';
        header( "Location: $url" );
        mysqli_close($link);
        exit;
    }

    // Check for duplicates in the database. If detected return to the registration page.
    function check($link, $query)
    {
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0)
        {
            return;
        }
        else 
        {
            $url = 'https://skopossecurity.com/register';
            header( "Location: $url" );
            mysqli_close($link);
            exit;
        }
    }

    // If all queries return no duplicates then the data may be stored in the database.
    $sql = "SELECT email FROM userdata WHERE email='$email';";
    check($link, $sql);
    $sql = "SELECT username FROM userdata WHERE username='$username';";
    check($link, $sql);
    $sql = "SELECT phonenumber FROM userdata WHERE phonenumber='$phonenumber';";
    check($link, $sql);

//
// Stores userdata into the database.
//
    // Prepare string for database insertion.
    $sql = "INSERT INTO userdata (email, username, usrpass, phonenumber, loggedin) VALUES ('$email', '$username', '$password', '$phonenumber', FALSE)";

    // If insertion is succesul return to login page, else display error.
    if(mysqli_query($link, $sql)){
        $url = 'https://skopossecurity.com/login';
        header( "Location: $url" );
    } else{
        echo "ERROR: Unable to complete insertion into database. " . mysqli_error($link);
    }

    // close connection
    mysqli_close($link);

//
// Makes ftp directory for the user.
//
    // Variables for ftp access.
    $ftpServer = "skopossecurity.com";
    $ftpUser = "ftpuser";
    $ftpPass = "juicy";

    // Set connection or exit script if failed.
    $ftpLink = ftp_connect($ftpServer) or die("Unable to connect to $ftpServer");

    // Attempt to login.
    if (@ftp_login($ftpLink, $ftpUser, $ftpPass)) 
    {
        echo "Connected as $ftpUser@$ftpServer";
    }
    else 
    {
        echo "Failed to connect as $ftpUser";
    }

    // Creates a personal folder for the newly registerd account.
    ftp_mkdir($ftpLink, $username);

    // Close server connection.
    ftp_close($ftpLink);

?>