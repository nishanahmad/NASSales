<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
	require '../functions/monthMap.php';		
	if(count($_POST)>0)
	{
		$month = $_POST['month'];
		$year = $_POST['year'];
		header("Location:generatePage.php?month=$month&year=$year");
	}
	$yearObjects = mysqli_query($con,"SELECT DISTINCT year FROM target ORDER BY year ASC");	
	$newYear = 0;
	foreach($yearObjects as $year)
	{
		$yearList[] = (int)$year['year'];
		$newYear =  $year['year'] + 1;
	}
	$yearList[] = $newYear;
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/bootstrap.min.css" rel="stylesheet">
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
<select  name="year">																				<?php
	foreach($yearList as $year)
	{																				?>
		<option  value="<?php echo $year;?>"> <?php echo $year;?> </option>			<?php
	}																								?>
</select>
<br><br>
<select  name="month">																				<?php
	for($i=1; $i<=12; $i++)
	{																								?>
		<option  value="<?php echo $i;?>"><?php echo getMonth($i);?></option>						<?php
	}																								?>
</select>
<br><br>
<input type="submit" name="submit" value="GENERATE">
</form>
</div>
<br><br>
<br><br>  
</body>
</html>																								<?php

}
else
	header("Location:../index.php");

