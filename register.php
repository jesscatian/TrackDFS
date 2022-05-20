<?php

require "config/config.php";

if ( !isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']) ) {
	$error = "Please fill out all required fields.";
}
else {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$statement_registered = $mysqli->prepare("SELECT * FROM users WHERE username=? OR email=?");
	$statement_registered->bind_param("ss", $_POST["username"], $_POST["email"]);
	$executed_registered = $statement_registered->execute();
	if(!$executed_registered) {
		echo $mysqli->error;
	} 
	$statement_registered->store_result();
	$numrows = $statement_registered->num_rows;
	$statement_registered->close();

  // var_dump($_POST);
	// echo "<hr> Number of rows: " . $numrows;

	if($numrows > 0) {
		$error = "Username or email address has already been taken. Please choose another one.";
	}
	else {
    $password = $_POST["password"];
    // $password = hash("sha256", $_POST["password"]);

		$statement = $mysqli->prepare("INSERT INTO users(email, password, username) VALUES(?,?,?)");
		$statement->bind_param("sss", $_POST["email"],$password, $_POST["username"]);

		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		$statement->close();
        header("Location: ../final-project/login.php");
		
	}
	$mysqli->close();
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="login-register.css">
</head>
<body class="text-center">
    <main class="form-signin">
      <form action="register.php" method="POST">
        <img class="mb-4" src="logo.png" alt=""  width="152" height="137">
        <h1 class="h3 mb-3 fw-normal" style="color:white;">Create Account</h1>
    
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" placeholder="tommytrojan" name="username">
          <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
          <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
          <label for="floatingPassword">Password</label>
        </div>

        <div class="col">
          <?php
          if (isset($error)){
            echo "<div class='text-info'>" . $error . "</div>";
          } 
          ?>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Create Account</button>
      </form>
      <div class="row">
			<div class="col">
                <p style="color:white;">Already have an account?</p>
				<a href="login.php" class="link-light">Sign in</a>
			</div>
		</div> <!-- .row -->
          <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
    </main>

  
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>