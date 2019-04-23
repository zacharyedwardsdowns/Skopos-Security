<?php
    // Set their username.
    $username = $_POST["username"];

    // Attempt MySQL server connection.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");

    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
    }

    // Check if no changes were made to a user's existing information.
    function check($link, $query, $info, $name)
    {
        // Query the database.
        $result = mysqli_query($link, $query);

        // Get the row out of result.
        $row = mysqli_fetch_array($result);

        // If the information is the same then return to settings and exit.
        if ($info == $row["$name"])
        {
            // Close sql connection.
            mysqli_close($link);

            // Return to the settings page then exit.
            $url = 'https://skopossecurity.com/user/settings';
            header( "Location: $url" );
            exit;
        }
    
    }

    // If email is set, update the database.
    if(!empty($_POST['em'])) 
    {
        $email = $_POST['em'];

        // Check if email is the same as current.
        $sql = "SELECT email FROM userdata WHERE username='$username';";
        check($link, $sql, $email, 'email');

        // Prepare string for database insertion.
        $sql = "UPDATE userdata SET email='$email' WHERE username='$username';";

        // If insertion is succesful then go to the user home page if phnnum is not set, else display an error.
        if(mysqli_query($link, $sql))
        {
            if(empty($_POST['phnnum']))
            {
                mysqli_close($link);
                $url = 'https://skopossecurity.com/user/home';
                header( "Location: $url" );
                exit;
            }
        } 
        else
        {
            echo "ERROR: Unable to complete insertion into database. " . mysqli_error($link);
        }
    }

    // If phonenumber is set, update the database.
    if(!empty($_POST['phnnum'])) 
    {
        $phonenumber = $_POST['phnnum'];

        // Check if phone number is the same as current.
        $sql = "SELECT phonenumber FROM userdata WHERE username='$username';";
        check($link, $sql, $phonenumber, 'phonenumber');

        // Prepare string for database insertion.
        $sql = "UPDATE userdata SET phonenumber='$phonenumber' WHERE username='$username';";

        // If insertion is succesful then go to the user home page, else display an error.
        if(mysqli_query($link, $sql))
        {
            mysqli_close($link);
            $url = 'https://skopossecurity.com/user/home';
            header( "Location: $url" );
            exit;
        } 
        else
        {
            echo "ERROR: Unable to complete insertion into database. " . mysqli_error($link);
        }
    }

    // If password is set, update the database.
    if(!empty($_POST['pass'])) 
    {
        $password = $_POST['pass'];

        // Check if the password matches, if not then return to the settings page.
        if($password !== $_POST['passcheck'])
        {
            // Close sql connection.
            mysqli_close($link);

            // Return to the settings page then exit.
            $url = 'https://skopossecurity.com/user/settings';
            header( "Location: $url" );
            exit;
        }

        // Check if phone number is the same as current.
        $sql = "SELECT usrpass FROM userdata WHERE username='$username';";
        check($link, $sql, $password, 'usrpass');

        // Prepare string for database insertion.
        $sql = "UPDATE userdata SET usrpass='$password' WHERE username='$username';";

        // If insertion is succesful then go to the user home page, else display an error.
        if(mysqli_query($link, $sql))
        {
            mysqli_close($link);
            $url = 'https://skopossecurity.com/user/home';
            header( "Location: $url" );
            exit;
        } 
        else
        {
            echo "ERROR: Unable to complete insertion into database. " . mysqli_error($link);
        }

    }

    // close connection.
    mysqli_close($link);

    // If nothing was set return to the settings page.
    $url = 'https://skopossecurity.com/user/settings';
    header( "Location: $url" );

?>
