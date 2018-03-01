<?php
session_start();
if(isset($_SESSION["user_name"]))
{

?>

<html>
<head>
<title>NAS SALES HOME PAGE</title>
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

  <div class="row">
<?php
	if($_SESSION["role"] == 'admin')
	{
?>	<button  class="btn lg ghost" onclick="location.href='adminPage.php'"><b>ADMIN PANEL</b></button>
    <br><br><br>		
<?php	
	}
?>	
	<button  class="btn lg ghost" onclick="location.href='sales/new.php'"><b>ADD NEW SALES</b></button>
    <br><br><br>

	<button  class="btn lg ghost" onclick="location.href='sales/todayList.php?ar=all'"><b>LIST TODAY'S SALES</b></button>
    <br><br><br>
	
	<button  class="btn lg ghost" onclick="location.href='company'"><b>COMPANY SALE</b></button>
    <br><br><br>	

	<button  class="btn lg ghost" onclick="location.href='sales/list.php?ar=all'"><b>LIST ALL SALES</b></button>
    <br><br><br><br>

		
   	<button  class="btn lg ghost" onclick="location.href='Target/ar_detailList.php'"><b>UPDATE AR DETAILS</b></button>
    <br><br><br>	
   	
   	<button  class="btn lg ghost" onclick="location.href='Target/'"><b>TARGET & POINTS</b></button>
    <br><br><br>		
	
	
	<button  class="btn lg ghost" onclick="location.href='SpecialTarget/'"><b>SPECIAL TARGET</b></button>
    <br><br><br>		
	
	
	<button  class="btn lg ghost" onclick="location.href='reports/'"><b>REPORTS</b></button>
    <br><br><br>

		
	<button  class="btn lg ghost" onclick="location.href='Salesforce'"><b>SALESFORCE</b></button>
    <br><br><br>	
		

	</div>

</div>
</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>