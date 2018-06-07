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
<title>SHEETS</title>
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
	<h1>SHEETS</h1>
	<br><br> 
	<a href="new.php" class="btn lg ghost">NEW DELIVERY</a>
	<br><br><br>

	<a href="list.php" class="btn lg ghost">LIST ALL SHEETS</a>
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