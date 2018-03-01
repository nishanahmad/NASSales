<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head >
<title>Reports</title>
<link rel="stylesheet" type="text/css" href="../css/reportpage.css" />
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker1" ).datepicker(pickerOpts);

});

$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker2" ).datepicker(pickerOpts);

});

</script>

</head>
 
<body>
<div class="background" align = "center">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeSilver.png' width='100px' height='100px'/> </a>
<br><br><br><br><br>
<form name="frmdate" method="post" action="generateReport.php">

<table border="0" cellpadding="1" cellspacing="10" width="25%" align="center">
<tr>
<td><b><font color="#989898">FROM :</font></b></td>
<b></b>
<td>
<input type="text" id="datepicker1" name="from" size="20" placeholder="From date" />
</td>
</tr>
<tr></tr><tr></tr>
<tr> 
<td><b><font color="#989898">TO :</font></b></td>
<b></b>
<td>
<input type="text" id="datepicker2" name="to" size="20" placeholder="To date"/>
</td>
</tr>
<tr></tr><tr></tr><tr></tr>
<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Generate" ></div></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>