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
				<title>SKOPOS - <?php echo ucfirst($username); ?>'s Schedules</title>
				<link rel="stylesheet" type="text/css" href="schedule.css"/>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
			</head>
            <body style="background-color:rgba(12, 170, 149);">
                
                <ul>
                    <li><a href="https://skopossecurity.com/user/home">Home</a></li>
                    <li><a class="active" href="https://skopossecurity.com/user/schedule">Schedule</a></li>
                    <li><a href="https://skopossecurity.com/user/settings">Settings</a></li>
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

                <!--Contains the Current Schedule-->
                <div class="container text-center">
                    <legend> <?php echo ucfirst($username); ?>'s Schedule </legend>
                    <form class = "row">
                    <h2>Current Schedule</h2>
                        <div class="column left">
                            <h3>Day</h3>
                            <p>Monday</p>
                        </div>
                        <div class="column middle">
                            <h3>Start</h3>
                            <p>10:15</p>
                        </div>
                        <div class="column right">
                            <h3>End</h3>
                            <p>18:00</p>
                        </div>
                    </form>
                </div>

                <!--Contains the Add to Schedule-->
                <div class="container text-center">
                    <form action = "/schedule.php" class = "row">
                    <h2>Add to Schedule</h2>
                        <div class="column left">
                            <h3>Day</h3>
                            <input type="text" name="day" placeholder="Day of the week"/>
                        </div>
                        <div class="column middle">
                            <h3>Start</h3>
                            <input type="text" name="hour" placeholder="Time to activate system"/>
                        </div>
                        <div class="column right">
                            <h3>End</h3>
                            <input type="text" name="minute" placeholder="Time to deactivate system"/>
                        </div>
                        <div id="submit" style="margin-top: 10px;">
                            <input type="submit" value="Add Schedule">
                        </div>
                    </form>
                    </div>

                <!--Contains the Your schedules-->
                <div class="container text-center">
                        <form class = "row">
                        <h2>Your Schedules</h2>
                            <div class="column left">
                                <h3>Day</h3>
                                <p>Monday</p>
                                <p>Tuesday</p>
                            </div>
                            <div class="column middle">
                                <h3>Start</h3>
                                <p>10:15</p>
                                <p>23:00</p>
                            </div>
                            <div class="column right">
                                <h3>End</h3>
                                <p>18:00</p>
                                <p>7:00</p>
                            </div>
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