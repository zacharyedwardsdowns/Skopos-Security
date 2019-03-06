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

	// If the user show their page, if not show an error.

	if (isset($_SESSION["sessionID"]) && $_SESSION["sessionID"] == $testSession[0])
        {
			?>

            <html>
			<head>
				<title>Skopos Security - User Settings</title>
				<link rel="stylesheet" type="text/css" href="register.css"/>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
			</head>
			<body style="background-color:rgba(0,0,0,0);">

            <!--THIS BUTTON EXECUTES LOG OUT-->
				<div class="col-sm-3">
					<form action="logout.php">
						<input type="submit" value="Logout" id="logout"/>
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