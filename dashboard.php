<?php
  require "config/config.php";
  // var_dump($_POST);




  // // Is user already in DB?
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
  // $sql = "select * from last_upload where users_id = " . $_POST["id"];
  // $results = $mysqli->query($sql);
  // if($results->num_rows > 1) {
  //   $statement = $mysqli->prepare("insert into last_upload(number_of_entries, entry_fees, win_rate, total_cash_winnings, profit, average_placement, users_id)
  //   values(?, ?, ?, ?, ?, ?, ?)");

  // } else {
  //   // add this upload to last upload table




  if(isset($_POST["numberOfEntries"])) {
    $sql = "insert into status(count, users_id) values(1," . $_SESSION["id"] . ")";
    $results = $mysqli->query($sql);


    $statement = $mysqli->prepare("insert into last_upload(number_of_entries, entry_fees, win_rate, total_cash_winnings, profit, average_placement, users_id)
    values(?, ?, ?, ?, ?, ?, ?)");
  
    $statement->bind_param("iiiiiii", $_POST["numberOfEntries"], $_POST["entryFees"], $_POST["winRate"], $_POST["totalCashWinnings"], $_POST["profit"], $_POST["averagePlacement"], $_SESSION["id"]);
  
    $executed = $statement->execute();
    if(!$executed) {
      echo $mysqli->error;
    }
  }




  $mysqli->close();
  // }






 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <!-- Bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Just an image -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="manage.php">
          <img src="logo-horizontal.png" alt="" width="100%" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="upload.php">Upload</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION["email"] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                <li><a class="dropdown-item" href="manage.php">Manage</a></li>
                <li><a class="dropdown-item" href="login.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <table class="table table-striped table-dark">
      <h1>Key Statistics</h1>
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
          <td> <?php echo $_POST["numberOfEntries"]; ?></td>
          <td><?php echo "$" . $_POST["entryFees"]; ?></td>
          <td><?php echo $_POST["winRate"] . "%"; ?></td>
          <td><?php echo "$" . $_POST["totalCashWinnings"]; ?></td>
          <td><?php echo "$" . $_POST["profit"]; ?></td>
          <td><?php echo $_POST["averagePlacement"] . "% Percentile"; ?></td>
        </tr>
      </tbody>
    </table>


    <div class="container">
      <div class="row">
        <div class="col">
          <div class="container2">
            <canvas id="myChart" width="75%" height="50%"></canvas>
          </div>
        
          </div>
          <div class="col">
            <div class="container2">
              <canvas id="myChart2" width="75%" height="50%"></canvas>
            </div>
          </div>
        </div>
      </div>

    

   
    
    <!-- D3 -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    
     <!-- Bootstrap -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

     
    <script>

      // d3.csv("history.csv", (d) => {
      //   console.log(d)
      //   numberOfEntries += 1

      //   entryFees += Number(d.Entry_Fee.slice(1))

      //   if(d.Place <= d.Places_Paid){
      //     winRate += 1
      //   }

      //   totalCashWinnings += Number(d.Winnings_Non_Ticket.slice(1))

      //   place += Number(d.Place)
      //   contestEntries += Number(d.Contest_Entries)
        
      //   console.log("Number of Entries: " + numberOfEntries)
      //   console.log("Entry Fees: " + entryFees)
      //   console.log("Win Rate: " + winRate)
      //   console.log("Total Cash Winnings: " + totalCashWinnings)

      //   profit = totalCashWinnings - entryFees
      //   console.log("Profit: " + profit)

      //   averagePlacement = place / contestEntries
      //   console.log("Average Placement: " + averagePlacement)

      //   if(d.Sport === "NBA"){
      //     nba += 1
      //     console.log("NBA: " + nba)
      //   }
      //   if(d.Sport === "MLB"){
      //     mlb += 1
      //     console.log("MLB: " + mlb)
      //   }
      //   if(d.Sport === "NHL"){
      //     nhl += 1
      //     console.log("NHL: " + nhl)
      //   }
      //   if(d.Sport === "MMA"){
      //     mma += 1
      //     console.log("MMA: " + mma)
      //   }
      //   if(d.Sport === "GOLF"){
      //     golf += 1
      //     console.log("GOLF: " + golf)
      //   }
      // })

    
      const ctx = document.getElementById('myChart').getContext('2d');
      const myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ['NBA', 'MLB', 'NFL', 'NHL', 'MMA', 'Golf'],
              datasets: [{
                  label: '# of Votes',
                  data: [<?php echo $_POST["nba"];?>, <?php echo $_POST["mlb"];?>, <?php echo $_POST["nfl"];?>, <?php echo $_POST["nhl"];?>, <?php echo $_POST["mma"];?>, <?php echo $_POST["golf"];?>],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
            plugins: {
            title: {
                display: true,
                text: 'Sports Played'
            }
        }
          }
      });

      const ctx2 = document.getElementById('myChart2').getContext('2d');
      const myChart2 = new Chart(ctx2, {
          type: 'pie',
          data: {
              labels: ['Classic', 'Showdown Captain Mode', 'KFC Captain Pick', 'Classic (Old)', 'Non Late Swap', 'Series'],
              datasets: [{
                  label: '# of Votes',
                  data: [12, 19, 3, 5, 2, 3],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
            plugins: {
            title: {
                display: true,
                text: 'Game Type'
            }
        }
          }
      });

      // const ctx3 = document.getElementById('myChart3').getContext('2d');
      // const myChart3 = new Chart(ctx3, {
      //     type: 'pie',
      //     data: {
      //         labels: ['Classic', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      //         datasets: [{
      //             label: '# of Votes',
      //             data: [12, 19, 3, 5, 2, 3],
      //             backgroundColor: [
      //                 'rgba(255, 99, 132, 0.2)',
      //                 'rgba(54, 162, 235, 0.2)',
      //                 'rgba(255, 206, 86, 0.2)',
      //                 'rgba(75, 192, 192, 0.2)',
      //                 'rgba(153, 102, 255, 0.2)',
      //                 'rgba(255, 159, 64, 0.2)'
      //             ],
      //             borderColor: [
      //                 'rgba(255, 99, 132, 1)',
      //                 'rgba(54, 162, 235, 1)',
      //                 'rgba(255, 206, 86, 1)',
      //                 'rgba(75, 192, 192, 1)',
      //                 'rgba(153, 102, 255, 1)',
      //                 'rgba(255, 159, 64, 1)'
      //             ],
      //             borderWidth: 1
      //         }]
      //     },
      //     options: {
      //       plugins: {
      //       title: {
      //           display: true,
      //           text: 'Game Type'
      //       }
      //   }
      //     }
      // });
    </script>
</body>
</html>