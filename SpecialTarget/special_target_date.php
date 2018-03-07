<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	if($_POST)
	{
		$fromDate = date("Y-m-d", strtotime($_POST["from"]));
		$toDate = date("Y-m-d", strtotime($_POST["to"]));
		
		$query = mysqli_query($con,"INSERT INTO special_target_date (from_date,to_date) VALUES ('$fromDate','$toDate') ") or die(mysqli_error($con));		 						
		
		header("Location:insertNewList.php?fromDate=$fromDate&toDate=$toDate");
	}
?>

<html>
<head >
<link rel="stylesheet" type="text/css" href="../css/reportpage.css" />
<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<link rel="stylesheet" href="../resources/demos/style.css">
<script type="text/javascript" src="../js/jQuery.min.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);
$( "#datepicker2" ).datepicker(pickerOpts);


});
</script>

</head>
 
<body>
<div class="background" align = "center">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeSilver.png' width='100px' height='100px'/> </a>
<br><br><br><br><br>
<form name="frm" method="post" action="">

<table border="0" cellpadding="1" cellspacing="10" width="25%" align="center">
<tr>
<td><b><font color="#989898">FROM :</font></b></td>
<b></b>
<td>
<input type="text" id="datepicker" name="from" required  size="20" placeholder="From Date"/>
</td>
</tr>
<tr></tr><tr></tr>
<tr> 
<td><b><font color="#989898">TO :</font></b></td>
<b></b>
<td>
<input type="text" id="datepicker2" name="to" required  size="20" placeholder="To date"/>
</td>
</tr>
<tr></tr><tr></tr><tr></tr>
<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Insert Range" onclick="return confirm('Do you want to insert new special target range?')"></div></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>																							<?php
}
else
	header("Location:../index.php");

