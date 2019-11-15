<?php
    session_start();

    $username = "";
    $email = "";
    $errors = array();

    // connect to the database
    $db = mysqli_connect("localhost","root","root","milestone4");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // if the register button is clicked
    if (isset($_POST['register'])){
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password1 = mysqli_real_escape_string($db, $_POST['password1']);
        $password2 = mysqli_real_escape_string($db, $_POST['password2']);

        // ensure that form fields are filled properly
        if (empty($username)) {
            array_push($errors, "User is required"); 
        }
        if (empty($email)) {
            array_push($errors, "Email is required"); 
        }   
        if (empty($password1)) {
            array_push($errors, "Password is required"); 
        }
        if ($password1 != $password2) {
            array_push($errors, "The two passwords do not match");
        }

        // if there are no errors, save user to database 
        if (count($errors) == 0) {
            $password = md5($password1);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            mysqli_query($db, $sql);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location:index.php'); //redirect to home page 
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
        <h2>Register</h2>
    </div>

    <form method="POST" action="registration.php">
    <!-- display valiadtion errors here -->
        <?php include ('errors.php'); ?>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password1">
        </div>
        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="password2">
        </div>
        <div class="input-group">
            <button type="submit" name="register" class="btn">Register</button>
        </div>
        <p>
            Already a member? <a href="login.php">Sign in</a> 
        </p>
    </form>
</body>
</html>