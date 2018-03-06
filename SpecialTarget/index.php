<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head>
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
  <h1>SPECIAL TARGET<h1>
  <br><br>
   	<button  class="btn lg ghost" onclick="location.href='updatePage.php'"><b>VIEW / UPDATE SPECIAL TARGET</b></button>
    <br><br><br>	   	
	
   	<button  class="btn lg ghost" onclick="location.href='achievement.php?'"><b>VIEW ACHIEVEMENT DETAILS</b></button>
    <br><br><br>	   		
	
	<button  class="btn lg ghost" onclick="location.href='special_target_date.php'"><b>INSERT SPECIAL TARGET DATE</b></button>
    <br><br><br>	
	</div>

</div>
</body>
</html>
<?php
}
else
header("../Location:loginPage.php");
?>