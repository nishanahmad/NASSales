<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';		
	if(count($_POST)>0)
	{
		$month = $_POST['month'];
		$year = $_POST['year'];
		header("Location:generatePage.php?month=$month&year=$year");
	}	
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<title>Select Month and Year</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div align="center" style="padding-bottom:5px;">

<h1>GENERATE TARGET LIST</h1>
<div style="width:100%;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
<br>
<br><br>
<form method="post" action="">
<select  name="year">
    <option  value="2016">2016</option>
    <option  value="2017">2017</option>
    <option  value="2018">2018</option>
    <option  value="2019">2019</option>
    <option  value="2020">2020</option>
    <option  value="2021">2021</option>
    <option  value="2022">2022</option>	
	<option  value="2023">2023</option>
	<option  value="2024">2024</option>
	<option  value="2025">2025</option>
</select>
<br><br>
<select  name="month">
    <option  value="1" selected>January</option>
    <option  value="2">Februaury</option>
    <option  value="3">March</option>
    <option  value="4">April</option>
    <option  value="5">May</option>
    <option  value="6">June</option>
    <option  value="7">July</option>	
	<option  value="8">August</option>
	<option  value="9">September</option>
	<option  value="10">October</option>
	<option  value="11">November</option>
	<option  value="12">December</option>	
</select>
<br><br>
<input type="submit" name="submit" value="GENERATE">
</form>
</div>
<br><br>
<br><br>  
</body>
</html>
<?php
}
else
	header("../Location:loginPage.php");

?>