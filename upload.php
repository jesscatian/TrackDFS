<?php
  require "config/config.php";





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
      <!-- CSS -->
      <link rel="stylesheet" href="upload.css">
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
            <a class="nav-link active" href="upload.php">Upload</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $_SESSION["email"] ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="manage.php">Manage</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- <form id="myform" action="dashboard.php" method="POST" enctype="multipart/form-data">
    <div class="upload text-center">
        
        <input type="file" name="FileUpload" id="myfile"/>
        <button type="button" onclick="myfunction()" class="btn btn-success">Upload</button>
 
  </form> -->

  <form id="myform" method="POST" action="dashboard.php">
   <input type="hidden" name="numberOfEntries" id="numberOfEntries">
   <input type="hidden" name="entryFees" id="entryFees">
   <input type="hidden" name="winRate" id="winRate">
   <input type="hidden" name="totalCashWinnings" id="totalCashWinnings">
   <input type="hidden" name="profit" id="profit">
   <input type="hidden" name="averagePlacement" id="averagePlacement">

   <input type="hidden" name="nba" id="nba">
   <input type="hidden" name="mlb" id="mlb">
   <input type="hidden" name="nhl" id="nhl">
   <input type="hidden" name="nfl" id="nfl">
   <input type="hidden" name="mma" id="mma">
   <input type="hidden" name="golf" id="golf">

   <input type="hidden" name="classic" id="classic">
   <input type="hidden" name="showdownCaptainMode" id="showdownCaptainMode">
   <input type="hidden" name="kfcCaptainPick" id="kfcCaptainPick">
   <input type="hidden" name="classicOld" id="classicOld">
   <input type="hidden" name="nonLateSwap" id="nonLateSwap">
   <input type="hidden" name="series" id="series">
   <div class="upload text-center">
     <h2>Instructions:</h2>
     <h4>1. Log into DraftKings using this      <a href="https:/draftkings.com/mycontests/">link</a></h4>    
     <h3>2. Download record .CSV file</h3>
     <h4>2. Upload .CSV below</h4>

     <br>
    <input id="myfile" name="files[]" multiple="" type="file" />
    </div>
</form>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
     

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script type="module">

    import {csvParse} from "https://cdn.skypack.dev/d3-dsv@3";

    document.forms['myform'].elements['myfile'].onchange = function(evt) {
      if(!window.FileReader) return; 

      var reader = new FileReader();

      reader.onload = function(evt) {
          if(evt.target.readyState != 2) return;
          if(evt.target.error) {
              alert('Error while reading file');
              return;
          }
          const data = csvParse(evt.target.result);
          
          let numberOfEntries = 0
          let entryFees = 0
          let winRate = 0
          let totalCashWinnings = 0.00
          let profit = 0
          let averagePlacement = 0
          let place = 0
          let contestEntries = 0

          let nba = 0
          let mlb = 0
          let nhl = 0
          let nfl = 0
          let mma = 0
          let golf = 0

          let classic = 0
          let showdownCaptainMode = 0
          let kfcCaptainPick = 0
          let classicOld = 0
          let nonLateSwap = 0
          let series = 0
          for(let i=0; i<data.length; i++){
            numberOfEntries += 1

            entryFees += parseFloat(data[i].Entry_Fee.slice(1).replace(/,/g,''))
            if(Number(data[i].Place) <= Number(data[i].Places_Paid)){
              winRate += 1
            }
           
            totalCashWinnings += parseFloat(data[i].Winnings_Non_Ticket.slice(1).replace(/,/g,''));

            place += Number(data[i].Place)
            contestEntries += Number(data[i].Contest_Entries)

            if(data[i].Sport === "NBA"){
              nba += 1
         
            }
            if(data[i].Sport === "NFL"){
              nfl += 1
         
            }
            if(data[i].Sport === "MLB"){
              mlb += 1
         
            }
            if(data[i].Sport === "NHL"){
              nhl += 1
         
            }
            if(data[i].Sport === "MMA"){
              mma += 1

            }
            if(data[i].Sport === "GOLF"){
              golf += 1
          
            }

            if(data[i].Game_Type === "Classic"){
              classic += 1
          
            }
            if(data[i].Game_Type === "Showdown Captain Mode"){
              showdownCaptainMode += 1
          
            }
            if(data[i].Game_Type === "KFC Captain Pick"){
              kfcCaptainPick += 1
          
            }
            if(data[i].Game_Type === "Classic (Old)"){
              classicOld += 1
          
            }
            if(data[i].Game_Type === "Non Late Swap"){
              nonLateSwap += 1
          
            }
            if(data[i].Game_Type === "Series"){
              series += 1
          
            }
          }
          profit = totalCashWinnings - entryFees
          averagePlacement = place / contestEntries
          winRate = winRate / numberOfEntries

          winRate = winRate * 100
          averagePlacement = averagePlacement * 100

          entryFees = entryFees.toFixed(2)
          winRate = winRate.toFixed(2)
          totalCashWinnings = totalCashWinnings.toFixed(2)
          profit = profit.toFixed(2)
          averagePlacement = averagePlacement.toFixed(2)


          console.log("Number of Entries: " + numberOfEntries)
          console.log("Entry Fees: " + entryFees)
          console.log("Win Rate: " + winRate)
          console.log("Total Cash Winnings: " + totalCashWinnings)
          console.log("Profit: " + profit)
          console.log("Average Placement: " + averagePlacement)
          console.log("NBA: " + nba)
          console.log("MLB: " + mlb)
          console.log("NHL: " + nhl)
          console.log("MMA: " + mma)
          console.log("GOLF: " + golf)

          
          document.querySelector("#numberOfEntries").value = numberOfEntries
          document.querySelector("#entryFees").value = entryFees
          document.querySelector("#winRate").value = winRate 
          document.querySelector("#totalCashWinnings").value = totalCashWinnings
          document.querySelector("#profit").value = profit
          document.querySelector("#averagePlacement").value = averagePlacement

          document.querySelector("#nba").value = nba
          document.querySelector("#mlb").value = mlb
          document.querySelector("#nfl").value = nfl
          document.querySelector("#nhl").value = nhl
          document.querySelector("#mma").value = mma
          document.querySelector("#golf").value = golf

          document.querySelector("#classic").value = classic
          document.querySelector("#showdownCaptainMode").value = showdownCaptainMode
          document.querySelector("#kfcCaptainPick").value = kfcCaptainPick
          document.querySelector("#classicOld").value = classicOld
          document.querySelector("#nonLateSwap").value = nonLateSwap

          document.querySelector("#myform").submit()

          
      };
      
      reader.readAsText(evt.target.files[0]);
    };

    </script>


</body>
</html>