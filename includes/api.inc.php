<?php
require_once "connection.php";

/**
 * The function adds a record to the watchlist table.
 */
function addtoWatchlist(
    $conn,
    $symbol,
    $company_name,
    $current_price,
    $predicted_price,
    $price_difference,
    $fiftyTwoWeekHigh,
    $trailingPE,
    $pegRatio,
    $priceToSales,
    $epsForward,
    $epsCurrentYear,
    $bookValue,
    $forwardPE,
    $priceToBook,
    $targetPriceHigh,
    $targetPriceLow,
    $targetPriceMean
) {

    if (isSymbolinStock($conn, 'watchlist', $symbol) == True) {

        return false;
    } else {

        $sql = "INSERT INTO tickerInfo (symbol, fiftyTwoWeekHigh, trailingPE, pegRatio, priceToSales, epsForward, epsCurrentYear,
        bookValue, forwardPE, priceToBook, targetPriceHigh, targetPriceLow,targetPriceMean) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../watchlist.php?error=tickerDNE");
            exit();
        }
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssss",
            $symbol,
            $fiftyTwoWeekHigh,
            $trailingPE,
            $pegRatio,
            $priceToSales,
            $epsForward,
            $epsCurrentYear,
            $bookValue,
            $forwardPE,
            $priceToBook,
            $targetPriceHigh,
            $targetPriceLow,
            $targetPriceMean
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $sql = "INSERT INTO watchlist (symbol, company_name, current_price, predicted_price, price_difference) VALUES (?,?,?,?,?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../watchlist.php?error=tickerDNE");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "sssss", $symbol, $company_name, $current_price, $targetPriceMean, $price_difference);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    return true;
}

/**
 * This function deletes records from the watchlist table where it matches the symbol.
 */

function deleteFromWatchlist($conn, $symbol)
{
    $sql = "DELETE FROM watchlist WHERE symbol = '$symbol';";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../watchlist.php?error=stmtfailed6");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM tickerInfo WHERE symbol = '$symbol';";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../watchlist.php?error=stmtfailed6");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/**
 * This function gets all the record in a list from the watchlist table.
 */
function getFromWatchlist($conn)
{
    $query = $conn->query("SELECT * FROM watchlist");

    $tickers = array();
    while ($result = $query->fetch_assoc()) {
        $tickers[] = $result;
    }

    return $tickers;
}

/**
 * This function gets all the records in a list 
 * from the tickerInfo table where it matches a ticker symbol.
 */
function getFromTickerInfo($conn, $symbol)
{
    $query = $conn->query("SELECT * FROM tickerInfo WHERE symbol = '$symbol';");

    $tickers = array();
    while ($result = $query->fetch_assoc()) {
        $tickers[] = $result;
    }

    return $tickers;
}


/**
 * This function gets all the ticker symbols in a list from the watchlist table.
 */
function getSymbols($conn)
{
    $query = $conn->query("SELECT symbol FROM watchlist;");

    $tickers = array();
    while ($result = $query->fetch_assoc()) {
        $tickers[] = $result;
    }
    return $tickers;
}


/**
 * This function checks if the stock you are trying to add to your 
 * watchlist is already in the database, meaning it has already been added.
 */

function isSymbolinStock($conn, $tableName, $symbol)
{

    $sql = "SELECT * FROM $tableName WHERE symbol = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../watchlist.php?error=stmtfailed7");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $symbol);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}


/**
 * This function gets stock info of argument $ticker 
 * calling the yahoo finance API and adding the the database.
 */
function apiTest($conn, $ticker)
{

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://yh-finance.p.rapidapi.com/market/v2/get-quotes?region=US&symbols=$ticker",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: yh-finance.p.rapidapi.com",
            "X-RapidAPI-Key: 93e247e15bmsheee48d2b6919a9cp124df0jsn5fcf4149edb3"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    $parse = json_decode($response);

    curl_close($curl);

    if ($err) {
        return false;
    } else {

        if (empty($parse->quoteResponse->result[0]->symbol)) {
            return false;
        } else {


            $symbol = $parse->quoteResponse->result[0]->symbol;

            $current_price = $parse->quoteResponse->result[0]->regularMarketPrice;

            $company_name = $parse->quoteResponse->result[0]->longName;

            $predicted_price = 300;



            $fiftyTwoWeekHigh = $parse->quoteResponse->result[0]->fiftyTwoWeekHigh;
            $trailingPE = $parse->quoteResponse->result[0]->trailingPE;
            $pegRatio = $parse->quoteResponse->result[0]->pegRatio;
            $priceToSales = $parse->quoteResponse->result[0]->priceToSales;
            $epsForward = $parse->quoteResponse->result[0]->epsForward;
            $epsCurrentYear = $parse->quoteResponse->result[0]->epsCurrentYear;
            $bookValue = $parse->quoteResponse->result[0]->bookValue;
            $forwardPE = $parse->quoteResponse->result[0]->forwardPE;
            $priceToBook = $parse->quoteResponse->result[0]->priceToBook;
            $targetPriceHigh = $parse->quoteResponse->result[0]->targetPriceHigh;
            $targetPriceLow = $parse->quoteResponse->result[0]->targetPriceLow;
            $targetPriceMean = $parse->quoteResponse->result[0]->targetPriceMean;

            $price_difference = round(((($targetPriceMean - $current_price) / $current_price) * 100), 2);

            $result = addtoWatchlist(
                $conn,
                $symbol,
                $company_name,
                $current_price,
                $predicted_price,
                $price_difference,
                $fiftyTwoWeekHigh,
                $trailingPE,
                $pegRatio,
                $priceToSales,
                $epsForward,
                $epsCurrentYear,
                $bookValue,
                $forwardPE,
                $priceToBook,
                $targetPriceHigh,
                $targetPriceLow,
                $targetPriceMean
            );
        }
    }

    return true;
}
