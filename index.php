<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Stock Watchlist</title>
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.waterwheelCarousel.js"></script>
  <link href="css/style.css" type="text/css" rel="stylesheet">
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css' integrity='sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU' crossorigin='anonymous'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="1.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">

li{
list-style:none;
}

h2 {
    display: table-cell;
    vertical-align: middle;
    /* height: inherit; */
    width: 100%;
    text-align: center;
    color: black;
    font-size: 22px;
}
    </style>
</head>
<body>
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 gfhfgh">
	<div class="side-watchlist" style="margin-left: 400px;">
	<div class="watchlist-h">
	<h4>watchlist</h4>
	</div>
  <br>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <input type="text" name="search" placeholder="Add here" style="margin-left: 26px;height: 30px;width: 100px;">
      <input type="submit" name="Add" value="+" class="btn btn-info" style="width: 30px;font-size: 14px;text-align: center;">
    <input type="text" name="del" placeholder="Delete" style="margin-left: 10px;height: 30px;width: 100px;">
      <input type="submit" name="delete" value="-" class="btn btn-danger" style="width: 30px;font-size: 14px;text-align: center;">
</form><br>
<?php
if(isset($_POST['Add']))
{
  $x=$_POST['search'];
$gAPI ='https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol='.$x.'&interval=1min&apikey=7LADMM80QNXP6CB5';
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_URL,$gAPI);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
$data = curl_exec($ch);
curl_close($ch);
$data=json_decode($data, true);
$y=$data["Meta Data"]["3. Last Refreshed"];
date_default_timezone_set('Asia/Kolkata');
$d=date("Y-m-d H:i:00");
if($d>$y)
{
  $z=$data["Time Series (1min)"][$y]["4. close"];
  $zz=$data["Time Series (1min)"][$y]["1. open"];
  $z1=($zz-$z)/$zz;
}
else
{
  $z=$data["Time Series (1min)"][$d]["4. close"];
  $zz=$data["Time Series (1min)"][$d]["1. open"];
  $z1=($zz-$z)/$zz;
} 
echo "<br>";
$conn = new mysqli('localhost', 'root', '', 'ospda1');
$sqlxx="SELECT symbol from tb1 where symbol='$x'";
$resultxx=$conn->query($sqlxx);
if($resultxx->num_rows>0)
{
  $sqlxx="UPDATE tb1 set close='$z' open='$z1' where symbol='$x'";
  $sql111 = "SELECT id,symbol, close, volume FROM tb1";
$result111= $conn->query($sql111);
if ($result111->num_rows > 0) {
    // output data of each row
    while($row111 = $result111->fetch_assoc()) {
      echo "<div class='watchlist-list'>";
      echo "<ul class='list-inline'>";
      echo "<li>".$row111["symbol"]."</li>";
      echo "<li>".$row111["close"]."</li>";
      echo "<li class='color w-no'>".$row111["volume"]."</li>";
      echo "</ul>";
      echo "</div>";
    }
}  
}
else{
$sql = "INSERT INTO tb1 (symbol, close, volume) VALUES ('$x',$z,$z1)";
if ($conn->query($sql) === TRUE) {
    
$sql1 = "SELECT id,symbol, close, volume FROM tb1";
$result = $conn->query($sql1);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      echo "<div class='watchlist-list'>";
      echo "<ul class='list-inline'>";
      echo "<li>".$row["symbol"]."</li>";
      echo "<li>".$row["close"]."</li>";
      echo "<li class='color w-no'>".$row["volume"]."</li>";
      echo "</ul>";
      echo "</div>";
    }
}
}}
$conn->close();
}
if(isset($_POST['delete']))
{
$x1=$_POST['del'];
$conn = new mysqli('localhost', 'root', '', 'ospda1');
$sql = "DELETE FROM tb1 WHERE symbol='$x1'";
if ($conn->query($sql) === TRUE) {
$sql2 = "SELECT symbol, close, volume FROM tb1";
$result = $conn->query($sql2);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      echo "<div class='watchlist-list'>";
      echo "<ul class='list-inline'>";
      echo "<li>".$row["symbol"]."</li>";
      echo "<li>".$row["close"]."</li>";
      echo "<li class='color w-no'>".$row["volume"]."</li>";
      echo "</ul>";
      echo "</div>";
    }
}
}
$conn->close();
}
?>



	</div>
	</div>

	</div>

</div>
</body>
</html>