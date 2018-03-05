<?php
session_start();
if(isset($_SESSION["user_name"]))
{					?>
<html>
	<head>
		<title>AR & Points</title>
		<link rel="stylesheet" type="text/css" href="../css/index.css" />
	</head>
	
	<body>
		<div class="background">
		</div>
		<div class="container">
			<div class="row">
				<a href="../index.php"><img alt='Add' title='Add New' src='../images/homesilver.png' width='80px' height='80px'/></a>
			</div>
		  </div>

		  <div class="row">
		  <h1>AR & POINTS<h1>
		  <br><br> 
			<button  class="btn lg ghost" onclick="location.href='ar_detailList.php'"><b>AR DETAILS</b></button>
			<br><br><br>	

			<button  class="btn lg ghost" onclick="location.href='../target'"><b>TARGET</b></button>
			<br><br><br>

			<button  class="btn lg ghost" onclick="location.href='../specialTarget'"><b>SPECIAL TARGET</b></button>
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