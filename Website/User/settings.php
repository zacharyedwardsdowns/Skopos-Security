<?php
	// Carries over session from login.
	session_start();

	// Check sessionID against the database.
	// Create connection to the database.
    $link = mysqli_connect("localhost", "root", "juicy", "skopos");
    
    // Check if connected. If not throw error.
    if($link === false)
    {
        die("ERROR: Unable to establish connection to the database: " . mysqli_connect_error()); // Function outputs the cause of the error after the string.
	}
	
	// Set their username.
    $username = $_SESSION["username"];

	// Check them against the database for a match.
    $sql = "SELECT sessionID FROM sessions WHERE username='$username';";
	$result = mysqli_query($link, $sql);
	$testSession = mysqli_fetch_row($result);

	// If the user then show their page, if not then show an error.
	if (isset($_SESSION["sessionID"]) && $_SESSION["sessionID"] == $testSession[0])
    {
	?>

    <html>
    <head>
        <title>SKOPOS - <?php echo ucfirst($username); ?>'s Settings</title>
        <link rel="stylesheet" type="text/css" href="settings.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    </head>
    <body style="background-color:rgba(12, 170, 149);">

        <ul>
            <li><a href="https://skopossecurity.com/user/home">Home</a></li>
            <li><a href="https://skopossecurity.com/user/schedule">Schedule</a></li>
            <li><a class="active" href="https://skopossecurity.com/user/settings">Settings</a></li>
            <li><form action="logout.php"><input type="submit" value="Logout" id="logout"/></form></li>
        </ul>

		<!--THIS BUTTON EXECUTES LOG OUT-->
		<form action="logout.php">
            <input type="submit" value="Logout" id="logout"/>
        </form>

        <div class="container text-center" id="logo">
            <a href="https://skopossecurity.com">
                <img src="../Pictures/Logo.png" height="200" width="200"/>
            </a>
        </div>

        <div class="container text-center">
            <legend>Account Settings</legend>
            <form method="post" action="https://www.skopossecurity.com/user/updateinfo">
                <p>Password Reset:</p>
                <input type="hidden" name="username" value="<?php echo $username; ?>" />
                <div class="row">
                    <input type="password" id="password" placeholder="New Password" size="35" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number,one uppercase and lowercase letter, and at least 8 or more characters" name="pass" required/>
                </div>
                <div class="row">
                    <input type="password" id="passwordcheck" placeholder="Confirm New Password" size="35" name="passcheck" required/>
                </div>
                <div class="row">
                    <input type="submit" value="Reset" id="update">
                </div>
            </form>
            <form method="post" action="https://www.skopossecurity.com/user/updateinfo">
                <p>Update Email or Phone Number:</p>
                <input type="hidden" name="username" value="<?php echo $username; ?>" />
                <div class="row">
                    <input type="email" id="email" placeholder="example@gmail.com" size="35" name="em" />
                </div>
                <div class="row">
                    <input type="text" id="phonenumber" placeholder="Phn: 1112223333" pattern="[0-9]{10}" size="35" maxlength="10" name="phnnum"/>
                </div>
                <div class="row">
                    <input type="submit" value="Update" id="update">
                </div>
            </form>

            <script>
                // If user clicks okay then delete the account, if they click cancel then do nothing.
                function ConfirmDelete()
                {
                    var answer = confirm("Are you sure you want to delete your account <?php echo $username; ?>?\nAll account and user data will be removed.\nPlease make sure to download any clips or images you wish to keep!");

                    if (answer) 
                    {
                        answer = 'https://www.skopossecurity.com/user/delaccount.php'
                    }
                    else
                    {
                        answer = ''
                    }

                    document.getElementById('delacc').action = answer;
                }
            </script>

            <form id='delacc' action='' method='post'/>
                <input type="hidden" name="username" value="<?php echo $username; ?>" />
                <input type="submit" value="Delete Account" id="delete" Onclick="ConfirmDelete()" />
            </form>
        </div>

    </body>
    </html>

	<?php
	}
	else
	{
		echo "Please login.";
	}
		
	// Close link to the database.
	mysqli_close($link);
	
?>