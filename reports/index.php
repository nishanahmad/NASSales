<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>
<html>
<head>
<title>Reports</title>
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
  <h1>Reports<h1>
  <br><br> 
   	<button  class="btn lg ghost" onclick="location.href='totalSalesAR.php'"><b>AR Total Sales</b></button>
    <br><br><br>	

   	<button  class="btn lg ghost" onclick="location.href='target_proRata.php?'"><b>AR Target Pro-Rata</b></button>
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