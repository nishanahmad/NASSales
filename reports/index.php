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
<title>Reports</title>
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
  <h1>Reports</h1>
  <br><br> 
   	<a href="totalSalesAR.php" class="btn lg ghost">AR Total Sales</a>
    <br><br><br>	

   	<a href="target_proRata.php?" class="btn lg ghost">AR Target Pro-Rata</a>
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