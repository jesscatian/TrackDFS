<?php
    require "config/config.php";

    // var_dump($_POST);

    if(empty($_POST["email"]) || empty($_POST["password"])){
        $error = "Please enter username and password.";
    } else {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($mysqli->connect_errno) {
            echo $mysqli->connect_error;
            exit();
        }
        $sql = "SELECT * FROM users
                WHERE email = '" . $_POST['email'] . "' AND password = '" . $_POST["password"] . "';";
        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        if($results->num_rows == 1) {
            $row = $results->fetch_assoc();

            $_SESSION["email"] =$row["email"];
            $_SESSION["password"] = $row["password"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];

            // var_dump($_SESSION["password"]);

            header("Location: ../final-project/upload.php");

        }
        else {
            $error = "Invalid username or password.";
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackDFS</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="login-register.css">
</head>
<body class="text-center">
    <main class="form-signin">
        <form action="login.php" method="POST">
        <img class="mb-4" src="logo.png" alt="" width="152" height="137">
        <h1 class="h3 mb-3 fw-normal" style="color:white;">Please sign in</h1>

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


        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
        <div class="row">
			<div class="col">
                <p style="color:white;">Don't have an account?</p>
				<a href="register.php" class="link-light">Create account</a>
			</div>
	    </div> <!-- .row -->
        <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
    </main>

  
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>