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
<title>COMPANY SALE</title>
<link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
    <h1><img alt='Add' title='Add New' src='../images/logo.png' width='300px' height='50px'/></h1>
    <h4></h4>
  </div>
  <hr />
  </div>
  
 
<br><br> 

  <div class="row">
	<a href="new.php" class="btn lg ghost">NEW</a>
    <br><br><br>

	<a href="report.php?" class="btn lg ghost">VARIANCE REPORT</a>
    <br><br><br>
	
	</div>

</div>
</body>
</html>																										<?php
}
else
	header("Location:../index.php");
