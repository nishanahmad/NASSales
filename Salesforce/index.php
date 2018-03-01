<?php
session_start();
if(isset($_SESSION["user_name"]))
{

?>

<html>
<head>
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
		
	<button  class="btn lg ghost" onclick="location.href='SalesForceNullify.php'"><b>SALESFORCE UPLOAD</b></button>
    <br><br><br>	
	
	<button  class="btn lg ghost" onclick="location.href='points_delete.php'"><b>SALESFORCE UPLOAD (POINTS)</b></button>
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