<?php
session_start();
if(isset($_SESSION["role"]) == 'admin')
{
?>	
<html>
<head>
<title>ADMIN PANEL</title>
<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
	<a href="index.php"><img alt='Add' title='Add New' src='images/homesilver.png' width='80px' height='80px'/></a>
  </div>
  <hr />
  </div>

  <div class="row">
  <h1>ADMIN PANEL<h1>
  <br><br>
   	<button  class="btn lg ghost" onclick="location.href='target/lock_unlockPage.php?'"><b>LOCK - UNLOCK TARGET</b></button>
    <br><br><br>	   	
	
   	<button  class="btn lg ghost" onclick="location.href='SpecialTarget/lock_unlockPage.php?'"><b>LOCK - UNLOCK SPECIAL TARGET</b></button>
    <br><br><br>	   		
	
</div>
</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>