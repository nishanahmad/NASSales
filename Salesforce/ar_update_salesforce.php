<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	$file = "sfdc_date.json";
	$last_date = json_decode(file_get_contents($file),true);	
	$last_date = date('d-M-Y', strtotime($last_date));
?>

<html>
<head >
<title>UpLoad To SalesForce</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker1" ).datepicker(pickerOpts);

});
</script>
</head>
 <body>
<div class="background" align = "center">
<img src="../images/Salesforce-Logo.jpg">
<br><br><br>
<b>Last Uploaded on <?php echo $last_date;?></b>
<br><br>
<form name="date" method="post" action="SalesForceUpdate.php">
<input type="text" id="datepicker1" name="date" size="20" placeholder="Select Date" />
<br><br>
<div align="center"><input type="submit" name="submit" value="Upload To Salesforce" ></div>
</form>
<br>
<a href="index.php">Click here to go back to previous page</a>
</div>
</div>
</body>
</html>

<?php
}
else
header("Location:../index.php");
?>