<?php
    session_start();
?>
<?php
    require_once "connection.php";
    require_once "api.inc.php";
    
    $tableName = "watchlist";
    $query = $_POST["query"];
    if (isset($_POST["submit1"])){
        $result = apiTest($conn, $query);

        if ($result == false && isSymbolinStock($conn, $tableName, $query) == true){

            header("location: ../watchlist.php?error=AlreadyinWatchlist");
        }
        else if ($result == false){
            header("location: ../watchlist.php?error=tickerDNE");
            exit();
        }
        else{   
            header('location: ../watchlist.php');
            exit();
        }
        
    } 
    elseif(isset($_POST["submit2"])){
        
        if (isSymbolinStock($conn, $tableName, $query ) == true){
            deleteFromWatchlist($conn, $query);
        }
        else{
            header("location: ../watchlist.php?error=NotInWatchlist");
            exit();
        }

        header('location: ../watchlist.php');
        exit();

    }else {
        header('location: ../watchlist.php?error=unknown');
                exit();
    }

    header('location: ../watchlist.php');

    exit();
    
?>
