<html>
<head>
	<title>Skopos Security - Registration</title>
	<link rel="stylesheet" type="text/css" href="register.css"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background-color:rgba(0,0,0,0);">
	<div class="container text-center" id="logo">
		<a href="https://skopossecurity.com">
			<img src="../Pictures/Logo.png" height="200" width="200"/>
		</a>
	</div>
	<div class="container">
		<div class="col-sm-6 text-center col-sm-offset-3" id="regbox">
			<form method="post">
				<div class="row">
					<input type="email" id="email" placeholder="Email*" size="45" required/>
				</div>
				<div class="row">
					<input type="text" id="username" placeholder="Username*" size="18" required/>
					<input type="text" id="phonenumber" placeholder="Phone Number" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" size="18" maxlength="10"/>
				</div>
				<div class="row">
					<input type="password" id="password" placeholder="Password*"  size="18" pattern="([a-zA-z0-9] |!|@|#|&|$|%|^|*)" maxlength="64" minlength="8" required/>
					<input type="password" id="passwordcheck" placeholder="Confirm Password*" size="18" required/>
				</div>
				<div class="row">
				<div class="g-recaptcha" data-sitekey="6Ld0eY0UAAAAANTimW_Y0h9T7hBG6e_yGGSsxIXG" style="transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;" required></div>
				</div>
				<div class="row">
					<input type="submit" id="regbutton" value="Register"/>
				</div>
				<div class="g-recaptcha" data-sitekey="6Ld0eY0UAAAAANTimW_Y0h9T7hBG6e_yGGSsxIXG" required></div>
			</form>
		</div>
	</div>
</body>
</html>
