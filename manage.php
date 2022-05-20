<?php
  

  $isUpdated = false;
  require "config/config.php";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($mysqli->connect_errno) {
      echo $mysqli->connect_error;
      exit();
  }

  $noLastUpload = false;
  $mysqli->set_charset('utf8');
  $sql = "SELECT * FROM last_upload where users_id = " . $_SESSION["id"] . " order by id desc limit 1";
  $results = $mysqli->query($sql);
  $row = $results->fetch_assoc();
  if($row == NULL){
    $noLastUpload = true;
  }


  $sql1 = "select count(count)
  from status
  where users_id = " . $_SESSION["id"];
  $results1 = $mysqli->query($sql1);
  $row1 = $results1->fetch_assoc();










  if(isset($_POST["newUsername"])){
    if(empty($_POST["newUsername"])){
      $error = "Please fill out all required fields.";
    } else {
      $statement = $mysqli->prepare("
        update users set
        username = ?
        where id = ?
      ");
      $statement->bind_param("si", $_POST["newUsername"], $_SESSION["id"]);
      $executed = $statement->execute();
      if(!$executed) {
        echo $mysqli->error;
      }
      if($statement->affected_rows == 1) {
          $isUpdated = true;
          // echo "Hello";
          $_SESSION["username"] = $_POST["newUsername"];
      }
      // echo $isUpdated;
      $statement->close();
    }
  }

  if(isset($_POST["newPassword"])){
    if(empty($_POST["newPassword"])){
      $error = "Please fill out all required fields.";
    } else {
      $statement = $mysqli->prepare("
        update users set
        password = ?
        where id = ?
      ");
      $statement->bind_param("si", $_POST["newPassword"], $_SESSION["id"]);
      $executed = $statement->execute();
      if(!$executed) {
        echo $mysqli->error;
      }
      if($statement->affected_rows == 1) {
          $isUpdated = true;
          echo "Hello";
          $_SESSION["password"] = $_POST["newPassword"];
      }
      echo $isUpdated;
      $statement->close();
    }
  }

  $mysqli->close();
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
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            background: linear-gradient(#02010A, #571F7D);
        }
        .row {
            margin-top: 5%;
        }
        .col {
            color: white;
            text-align: center;
        }

        .container {
            /* margin-top: 10% */
        }
        table {
          text-align:center;
        }
        h1 {
            margin-top: 5%;
            text-align: center;
            color: white;
        }
        h2 {
          color: gray;
        }
    </style>
</head>
<body>
    <!-- Just an image -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="logo-horizontal.png" alt="" width="100%" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="upload.php">Upload</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION["username"] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
    <h1>Manage Account</h1>
        <div class="row">
            <div class="col">
                Username
            </div>
            <div class="col">
                <?php echo $_SESSION["username"] ?> 
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                Edit
                </button> 
            </div>
        </div>
        <div class="row">
            <div class="col">
                Password
            </div>
            <div class="col">
                ******
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                Edit
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Number of Uploads
            </div>
            <div class="col">
                <?php echo $row1["count(count)"];?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Status
            </div>
            <div class="col">
              <?php 
              if($row1["count(count)"] < 5){
                echo "Koala (Free)";
              } 
              if($row1["count(count)"] >= 5 && $row1["count(count)"] <= 10){
                echo "Bear (Premium)";
              } 
              if($row1["count(count)"] > 10){
                echo "Panther (Expert)";
              } 
              ?>
            </div>
        </div>
        <table class="table table-striped table-dark">
        <h2>Last Upload</h2>
        <thead>
          <tr>
            <th scope="col">Number of Entries</th>
            <th scope="col">Entry Fees</th>
            <th scope="col">Win Rate</th>
            <th scope="col">Total Cash Winnings</th>
            <th scope="col">Profit</th>
            <th scope="col">Average Placement</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <?php 
              if($noLastUpload == true){
                echo "N/A";
              }else {
                echo $row["number_of_entries"];
              }
        
              ?></td>
            <td><?php 
            if($noLastUpload == true){
              echo "N/A";
            }else {
              echo "$" . $row["entry_fees"];
            }
            
           ?></td>
            <td>
            <?php
               if($noLastUpload == true){
                echo "N/A";
              }else {
                echo $row["win_rate"] . "%";
              } ?></td>
            <td><?php 
            if($noLastUpload == true){
              echo "N/A";
            }else {
              echo "$" . $row["total_cash_winnings"];
            }?></td>
            <td><?php 
            if($noLastUpload == true){
              echo "N/A";
            }else {
              echo "$" . $row["profit"];
            }
            
         ?></td>
            <td><?php 
             if($noLastUpload == true){
              echo "N/A";
            }else {
              echo $row["average_placement"] . "% Percentile" ;
            }
            
   ?></td>
          </tr>
        </tbody>
      </table>


        <div class="row">
          <div class="col">
          <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')" href="delete.php">Delete Account</a>
           </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="manage.php" method="POST">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enter new password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" placeholder="Password" name="newPassword">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </form>
    </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="manage.php" method="POST">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter new username</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Username" name="newUsername">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </form>
        </div>
        </div>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>