<?php
include_once 'header.php';
include_once 'includes/api.inc.php';
include_once 'includes/connection.php';
?>

<main>
  <link rel='stylesheet' href='css/watchlist.css' />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.2.8/d3.min.js" type="text/JavaScript"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

  <style>
    body {
      background-image: url('img/stock_back.jpg');
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <br></br>

  <form method="post" action="includes/watchlist.inc.php">


    <div class=box4>
      <p id="txt">Add a stock to your watchlist:</p>
      <input style="width:100%;" type="text" id="query" name="query" placeholder="Ticker to add...">
      <p></p>
      <input style="float:right;" type="submit" id="addBtn" name="submit1" value="">
      <input type="submit" id="removeBtn" name="submit2" value="">
    </div>

  </form>

  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "tickerDNE") {
      echo "<script> alert('This ticker does not exist!');</script>";
    } elseif ($_GET["error"] == "AlreadyInWatchlist") {
      echo "<script> alert('This stock is already in your watchlist!');</script>";
    } elseif ($_GET["error"] == "NotInWatchlist") {
      echo "<script> alert('This stock is not in your watchlist!');</script>";
    } elseif ($_GET["error"] == "unknown") {
      echo "<script> alert('Unknown error occured!');</script>";
    }
  }

  ?>

  <div class=box2>
    <h2 style="color:whitesmoke;">My Watchlist <i class="icon-minus"></i></h2>
    <table>
      <thead>
        <tr>
          <th>Stock Ticker</th>
          <th>Stock Name</th>
          <th>Current Price</th>
          <th>1-Year Estimated Price</th>
          <th>Prediction Price Difference</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $symbArr = array();
        $priceArr = array();
        $predpriceArr = array();
        $watchlistData = getFromWatchlist($conn);
        for ($i = 0; $i < count($watchlistData); $i++) {
          $symbArr[$i] = $watchlistData[$i]["symbol"];
          $symbol = $watchlistData[$i]["symbol"];
          $name = $watchlistData[$i]["company_name"];
          $price = $watchlistData[$i]["current_price"];
          $priceArr[$i] = $watchlistData[$i]["current_price"];
          $predicted_price = $watchlistData[$i]["predicted_price"];
          $predpriceArr[$i] = $watchlistData[$i]["predicted_price"];
          $difference = $watchlistData[$i]["price_difference"];

          echo "<tr>";
          echo "<td>$symbol</td>";
          echo "<td>$name</td>";
          echo "<td>$ $price</td>";
          echo "<td>$ $predicted_price</td>";
          echo "<td>$difference%</td>";
          echo "</tr>";
        }

        ?>
      </tbody>
    </table>
  </div>

  <body>

    <div style="width:30%;height:20%;display:flex;">
      <p style="color:white;">Current Prices:</p>
      <canvas id="chartjs_bar"></canvas>
      <p style="color:white;">1-Yr Estimated Prices:</p>
      <canvas id="chartjs_bar2"></canvas>
      <p style="color:white;">Watchlist Distribution:</p>
      <canvas id="chartjs_bar3"></canvas>

    </div>

  </body>

  <script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($symbArr); ?>,
        datasets: [{
          backgroundColor: [
            "#5969ff",
            "#ff407b",
            "#25d5f2",
            "#ffc750",
            "#2ec551",
            "#7040fa",
            "#ff004e"
          ],
          data: <?php echo json_encode($priceArr); ?>,
        }]
      },
      options: {
        legend: {
          display: false,
          position: 'bottom',

          labels: {
            fontColor: '#71748d',
            fontFamily: 'Circular Std Book',
            fontSize: 14,
          }
        },


      }
    });

    var ctx = document.getElementById("chartjs_bar2").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      title: {
        text: "Predicted Prices"
      },
      data: {
        labels: <?php echo json_encode($symbArr); ?>,
        datasets: [{
          backgroundColor: [
            "#5969ff",
            "#ff407b",
            "#25d5f2",
            "#ffc750",
            "#2ec551",
            "#7040fa",
            "#ff004e"
          ],
          data: <?php echo json_encode($predpriceArr); ?>,
        }]
      },
      options: {
        legend: {
          display: false,
          position: 'bottom',

          labels: {
            fontColor: '#71748d',
            fontFamily: 'Circular Std Book',
            fontSize: 14,
          }
        },


      }
    });

    var ctx = document.getElementById("chartjs_bar3").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      title: {
        text: "Predicted Prices"
      },
      data: {
        labels: <?php echo json_encode($symbArr); ?>,
        datasets: [{
          backgroundColor: [
            "#5969ff",
            "#ff407b",
            "#25d5f2",
            "#ffc750",
            "#2ec551",
            "#7040fa",
            "#ff004e"
          ],
          data: <?php echo json_encode($priceArr); ?>,
        }]
      },
      options: {
        legend: {
          display: false,
          position: 'bottom',

          labels: {
            fontColor: '#71748d',
            fontFamily: 'Circular Std Book',
            fontSize: 14,
          }
        },


      }
    });
  </script>

  <br></br>
  <br></br>

</main>

<?php
include_once 'footer.php'
?>