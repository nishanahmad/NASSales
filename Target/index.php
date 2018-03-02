<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	$year = date("Y");
	$month = date("m") - 1;
	echo $year;
	echo $month;	
?>

<html>
<head>
<title>TARGET & POINTS</title>
<link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
	<a href="../index.php"><img alt='Add' title='Add New' src='../images/homesilver.png' width='80px' height='80px'/></a>
  </div>
  <hr />
  </div>

  <div class="row">
  <h1>TARGET & POINTS<h1>
  <br><br> 
   	<button  class="btn lg ghost" onclick="location.href='updatePage.php?year=<?php echo $year;?>&month=<?php echo $month;?>'"><b>VIEW / UPDATE TARGET & RATE</b></button>
    <br><br><br>	

   	<button  class="btn lg ghost" onclick="location.href='generateDateSelectPage.php'"><b>GENERATE TARGET & RATE</b></button>
    <br><br><br>

    <button  class="btn lg ghost" onclick="location.href='calculatePointsTable.php?month=<?php echo $month;?>&year=<?php echo $year;?>'"><b>VIEW POINTS</b></button>
    <br><br><br>
	
	</div>

</div>
</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>