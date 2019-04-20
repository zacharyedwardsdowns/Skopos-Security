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
			<title>SKOPOS - <?php echo ucfirst($username); ?>'s Settings</title>
			<link rel="stylesheet" type="text/css" href="register.css"/>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
		</head>
		<body style="background-color:rgba(12, 170, 149);">

        <!--THIS BUTTON EXECUTES LOG OUT-->
			<div class="col-sm-3">
				<form action="logout.php">
					<input type="submit" value="Logout" id="logout"/>
				</form>
			</div>

			<div class="container text-center" id="logo">
				<a href="https://skopossecurity.com">
					<img src="../Pictures/Logo.png" height="200" width="200"/>
				</a>
			</div>

			<div class="container">
				<div class="col-sm-6 text-center col-sm-offset-3" id="regbox">
					<form method="post" action="https://www.skopossecurity.com/regaction">
						<h2>Setting</h2>
						<form action="/setting.php">
  							<fieldset>
    							<legend>Account information:</legend>
    							Username:<br>
   								<input type="text" id="username" placeholder="Username*" size="35" name="uname" />
								<br><br>
    							Password:<br>
    							<input type="password" id="password" placeholder="Password*"  size="35" pattern="([a-zA-z0-9] |!|@|#|&|$|%|^|*)" maxlength="64" minlength="8" name="pass"/>
   								<br><br>
    							Confirm Password:<br>
    							<input type="password" id="passwordcheck" placeholder="Confirm Password*" size="35" name="passcheck"/>
    							<br><br>
    							Phone Number:<br>
    							<input type="text" id="phonenumber" placeholder="Phn: 1112223333" pattern="[0-9]{10}" size="35" maxlength="10" name="phnnum"/>
								<br><br>
    							Email:<br>
    							<input type="email" id="email" placeholder="example@gmail.com*" size="35" name="em" />
    							<br><br>

    							<font color="brown"><input type="submit" value="Submit">
    							<font color="blue"><button type="reset"  value="Reset">Reset</button>

								<script>
									function ConfirmDelete()
									{
  										confirm ("Are you sure you want to delete account?");
									}
								</script>
									
								<form name='del_update' action='settings.php?dealer_id=10&usnamer=1' method='post'/>
									<font color="red"> <input type="submit" value="Delete Account" Onclick="ConfirmDelete()" />
									<input type="hidden" name="id" value="41" />
									<input type="hidden" name="url" value="settings.php?dealer_id=10&username=1" />
								</form>
  							</fieldset>
						</form>
					</form>
				</div>
			</div>

		</body>
		</html>

	<?php
	}
	else
	{
		echo "Please login.";
	}

	if(isset($_REQUEST['username']))
	{
		$username=$_REQUEST['username'];
		$qry="delete from user where username=$username";
		$cd=$_REQUEST['username'];

		if(mysql_query($qry))
		{
			$msg=" Deleted Successfully";
		}
		else
		{
			$msg="Error Deleting";
		}

		$url=$_REQUEST['url'];
		header("Location:$url");
	}
		
	// Close link to the database.
	mysqli_close($link);
	
?>