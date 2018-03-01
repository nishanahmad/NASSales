<?php
	if(count($_POST)>0) 
	{
		
		$url= "editSales.php?sales_id=".$_POST['sales_id']."";
		header( "Location: $url" );
	}
?>

<html>
<head >
<link rel="stylesheet" type="text/css" href="reportpage.css" />
</head>
 
<body>
<div class="background" align = "center">
<a href="index.php" class="link"><img alt='home' title='home' src='images/homeSilver.png' width='100px' height='100px'/> </a>
<br><br><br><br>
<form name="frmdate" method="post" action="inputSalesId.php">
<table border="0" cellpadding="1" cellspacing="10" width="350">
<td><div align="center"><input type="text" name="sales_id" class="txtField" placeholder="Enter Sales Id here"></div></td>
<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Edit" ></div></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>