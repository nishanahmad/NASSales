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
<title>SALESFORCE</title>
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
		
	<a href="SalesForceNullify.php" class="btn lg ghost">SALESFORCE UPLOAD</a>
    <br><br><br>	
	
	<a href="points_delete.php" class="btn lg ghost">SALESFORCE UPLOAD (POINTS)</a>
    <br><br><br>		
	

	</div>

</div>
</body>
</html>																		<?php
}
else
	header("Location:../index.php");
