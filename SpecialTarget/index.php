<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<title>SPECIAL TARGET</title>
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
  <h1>SPECIAL TARGET</h1>
  <br><br>
   	<a href="updatePage.php" class="btn lg ghost">VIEW / UPDATE SPECIAL TARGET</a>
    <br><br><br>	   	
	
   	<a href="achievement.php?" class="btn lg ghost">VIEW ACHIEVEMENT DETAILS</a>
    <br><br><br>	   		
	
	<a href="special_target_date.php" class="btn lg ghost">INSERT SPECIAL TARGET DATE</a>
    <br><br><br>	
	
	<a href="marchTemp.php" class="btn lg ghost">MARCH REPORT TEMPORARY</a>
    <br><br><br>		
	</div>

</div>
</body>
</html>																				<?php
}
else
	header("Location:../index.php");

