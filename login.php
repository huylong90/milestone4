<?php
	session_start();

	$username = "";
	$errors = array();

	// connect to the database
	$db = mysqli_connect('localhost','root','root','milestone4');

	// Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

	// log user in from login page 
	if (isset($_POST['login'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		// Data validation
		if (empty($username)) {
			array_push($errors, "User is required"); 
		}
		if (empty($password)) {
			array_push($errors, "Password is required"); 
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM users WHERE username='$username' AND password = '$password'";
			$result = mysqli_query($db, $query);
			if (mysqli_num_rows($result)==1){
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
				header('location:index.php'); //redirect to home page 
			}else{
				array_push($errors, " wrong username/password combination");
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>User registration system using PHP andMySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Log In</h2>
	</div>

	<form method="post" action="login.php">
		<!-- display valiadtion	errors here -->
		<?php include ('errors.php'); ?>
		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username">
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password">
		<div class="input-group">
			<button type="submit" name="login" class="btn">Login</button>
		</div>
		<p>
			Not yet a member? <a href="registration.php">Sign up</a> 
		</p>
	</form>
</body>
</html>