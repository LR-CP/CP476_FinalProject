<?php
    session_start();
?>
<?php


if (isset($_POST["tickersearch"])){

    $ticker = $_POST["tickersearch"];
    $url = 'https://finance.yahoo.com/quote/'.$ticker;
    var_dump($url);
    header("Location: $url");
}else {
    echo"<p>ERROR</p>";
}
    
?>
