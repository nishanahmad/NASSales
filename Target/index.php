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
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<title>TARGET & POINTS</title>
<link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
	<div class="row">
		<a href="../index.php"><img alt='Add' title='Add New' src='../images/homeSilver.png' width='80px' height='80px'/></a>
	</div>
	<hr />
</div>

<div class="row">
	<h1>TARGET & POINTS</h1>
	<br><br> 
	<a href="updatePage.php?year=<?php echo $year;?>&month=<?php echo $month;?>" class="btn lg ghost">VIEW / UPDATE TARGET & RATE</a>
	<br><br><br>	

	<a href="generateDateSelectPage.php" class="btn lg ghost">GENERATE TARGET & RATE</a>
	<br><br><br>

	<a href="calculatePointsTable.php?month=<?php echo $month;?>&year=<?php echo $year;?>" class="btn lg ghost">VIEW MONTHLY POINTS</a>
	<br><br><br>
	
	<a href="../points_full/mainPage.php?month=<?php echo $month;?>&year=<?php echo $year;?>&dateString=FULL" class="btn lg ghost">VIEW ACCUMULATED POINTS</a>
	<br><br><br>	
	
	<a href="companyTarget.php?month=<?php echo $month;?>&year=<?php echo $year;?>" class="btn lg ghost">COMPANY TARGET</a>
	<br><br><br>	

</div>

</div>
</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>