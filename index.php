<?php
session_start();
if(isset($_SESSION["user_name"]))
{																						?>
<html>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<head>
<title>HOME</title>
<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
    <h1><img alt='Add' title='Add New' src='images/logo.png' width='300px' height='50px'/></h1>
    <h4></h4>
  </div>
  <hr />
  </div>
  
 
<br><br> 

	<div class="row">																	<?php
	if($_SESSION["role"] == 'admin')
	{																					?>	
		<a href="admin/" class="btn lg ghost">ADMIN PANEL</a>
		<br><br><br>																	<?php	
	}																					?>	
	
	<a href="sales/todayList.php?ar=all" class="btn lg ghost">TODAY SALES</a>
    <br><br><br>

	<a href="sales/list.php" class="btn lg ghost">ALL SALES</a>
    <br><br><br><br>
	
	<a href="company/" class="btn lg ghost">COMPANY SALE</a>
    <br><br><br>	
		
   	<a href="ar/" class="btn lg ghost">AR DETAILS & POINTS</a>
    <br><br><br>	
	
	<a href="reports/" class="btn lg ghost">REPORTS</a>
    <br><br><br>

		
	<a href="Salesforce/" class="btn lg ghost">SALESFORCE</a>
    <br><br><br>	

	</div>
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");
?>