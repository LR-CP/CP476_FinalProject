<?php
include_once 'header.php';
include_once 'includes/api.inc.php';
include_once 'includes/connection.php';
?>

<main>
  <link rel='stylesheet' href='css/watchlist.css' />
  <link rel="stylesheet" href="css/index.css">

  <style>
    body {
      background-image: url('img/stock_back.jpg');
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <br></br>

  <form method = "post">
    <select id='userSelect1' name='userSelect1'>
    <option value="">----SELECT----</option>
      <?php
          $symbols= getSymbols($conn);

          for ($x = 0; $x < count($symbols); $x++) {
              $temp = $symbols[$x]["symbol"];
              echo "<option value=$temp>$temp</option>";
          }
      ?>
  </select>
  <input type="submit" name="Submit" value="Send">

  </form>

  <?php

    if (isset($_POST["userSelect1"])){

      echo"<table>
      <thead>
        <th>Type</th>
        <th>Value</th>
      </thead>
      <tbody>
      ";

      $value = $_POST["userSelect1"];   

    $getFromTickerInfo = getFromTickerInfo($conn, $value);
    for ($i = 0; $i < count($getFromTickerInfo); $i++) {
      $symbol = $getFromTickerInfo[$i]["symbol"];
      $fiftyTwoWeekHigh = $getFromTickerInfo[$i]["fiftyTwoWeekHigh"];
      $trailingPE = $getFromTickerInfo[$i]["trailingPE"];
      $pegRatio = $getFromTickerInfo[$i]["pegRatio"];
      $priceToSales = $getFromTickerInfo[$i]["priceToSales"];
      $epsForward = $getFromTickerInfo[$i]["epsForward"];
      $epsCurrentYear = $getFromTickerInfo[$i]["epsCurrentYear"];
      $bookValue = $getFromTickerInfo[$i]["bookValue"];
      $forwardPE = $getFromTickerInfo[$i]["forwardPE"];
      $priceToBook = $getFromTickerInfo[$i]["priceToBook"];
      $targetPriceHigh = $getFromTickerInfo[$i]["targetPriceHigh"];
      $targetPriceLow = $getFromTickerInfo[$i]["targetPriceLow"];
      $targetPriceMean = $getFromTickerInfo[$i]["targetPriceMean"];

      echo "<tr>";
      echo "<td> Symbol</td>";
      echo "<td>$symbol</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Fifty Two Week High</td>";
      echo "<td>$ $fiftyTwoWeekHigh</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Trailing PE</td>";
      echo "<td>$trailingPE</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Peg Ratio</td>";
      echo "<td>$pegRatio</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Price To Sales</td>";
      echo "<td>$priceToSales</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Eps Forward</td>";
      echo "<td>$epsForward</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Eps Current Year</td>";
      echo "<td>$epsCurrentYear</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Book Value</td>";
      echo "<td>$ $bookValue</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Forward PE</td>";
      echo "<td>$forwardPE</td>";
      echo "</tr>";      
      echo "<tr>";
      echo "<td> Price To Book</td>";
      echo "<td>$priceToBook</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Target Price High</td>";
      echo "<td>$ $targetPriceHigh</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Target Price Low</td>";
      echo "<td>$ $targetPriceLow</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td> Target Price Mean</td>";
      echo "<td>$ $targetPriceMean</td>";      
      echo "</tr>";
      echo "</td>";
    }
    echo"
    </tbody>
  </table>";

  }
  ?>

  </div>

</form>

</main>

<?php
include_once 'footer.php'
?>