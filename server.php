<?php

session_start();

//initialising variables

$username = "";
$email = "";

$errors = array();

//connect to db 

$db = mysqli_connect('localhost', 'root', 'root', 'milestone4') or die("could not connect to database");

//Register users

$username = mysqli_real_escape_string($db, $_POST['username']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$password_1 = mysqli_real_escape_string($db, $_POST['password']);
$password_2 = mysqli_real_escape_string($db, $_POST['password']);

//form validation 

if (empty($username)) {
	array_push($errors, "Username is required");	
}
if (empty($email)) {
	array_push($errors, "Email is required");	
}
if (empty($password_1)) {
	array_push($errors, "Password is required");	
}
if ($password_1 != $password_2) {
	//array_push($errors, "Passwords do not match");
}


// check db for existing user with same username 

$user_check_query = "SELECT * FROM user WHERE username = '$username' or email = '$email' LIMIT 1";

$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);

if ($user) {
	
	if ($user['username'] === $username){
		array_push($errors, "Username already exists");
	}
	
	if ($user['email'] === $email){
		array_push($errors, "This email is already has a registered username");
	}
}


//Register the user if no error in the form  

if(count($errors) == 0){

	$password = md5($password_1); //this will encrypt the password
	print $password;
	$query = "INSERT INTO user (username, email, password) VALUES ('$username','$email', '$password')";
	mysqli_query($db, $query);
	$_SESSION['username'] = $username; 
	$_SESSION['success'] = "you are now logged in";
	header("location: index.php");
} 

//Login User

if(isset($_POST['login_user'])){

	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	if (empty($username)) {
		array_push($errors,"Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	if (count($errors) == 0){
		$password = md5($password_1);
		$query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
		$result = mysqli_query($db, $query);

		if (mysqli_num_rows($result)) {
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "Logged in succesffuly";
			header('location: index.php');
		}else{
			array_push($errors, "Wrong username/password combination. Please try again.");
		}
	}
}
?>