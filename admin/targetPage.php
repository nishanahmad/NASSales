<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(isset($_SESSION["user_name"]))
{
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	
		
	require '../connect.php';  

	if(isset($_GET["year"]))
		$year = $_GET["year"];
	else
		$year = date("Y");
	$result = mysqli_query($con,"SELECT * FROM target_locker WHERE year='" . $year . "'") or die(mysqli_error($con));				 
?>

<html>
<style type="text/css">
.monthFont{
  font: 400 30px/1.3 'Lobster Two', Helvetica, sans-serif;
  color: #2b2b2b;
  text-shadow: 1px 1px 0px #ededed, 4px 4px 0px rgba(0,0,0,0.15);
}
</style>
<script type="text/javascript">
function rerender()
{
var year = document.getElementById("jsYear").value;

var hrf = window.location.href;
hrf = hrf.slice(0,hrf.indexOf("?"));

window.location.href = hrf +"?year="+ year;
}
</script>
<head>
<title>LOCK-UNLOCK</title>
<link rel="stylesheet" type="text/css" href="../newEdit.css" />
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>
</div>
<br><br>
<div align ="center">
<select id="jsYear" name="jsYear" onchange="return rerender();" style="width:80px;height:30px;font-weight:bold;">
    <option <?php if($year==2016) echo 'Selected';?> value="2016">2016</option>
    <option <?php if($year==2017) echo 'Selected';?> value="2017">2017</option>
    <option <?php if($year==2018) echo 'Selected';?> value="2018">2018</option>
    <option <?php if($year==2019) echo 'Selected';?> value="2019">2019</option>
    <option <?php if($year==2020) echo 'Selected';?> value="2020">2020</option>	
</select>
<br><br>
<table border="1" cellpadding="5" cellspacing="0" width="25%" align="center" class="tblSaveForm">
<?php

while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
{
	$dateObj   = DateTime::createFromFormat('!m', $row['month']);
	$month = $dateObj->format('F');	?>
	<td class="monthFont" width="70%"><?php echo $month;?></td>									<?php 
		if($row['locked'] == 1)
		{
?> 			<td align="center"><a href='targetUnlock.php?year=<?php echo $year;?>&month=<?php echo $row['month'];?>'><img src='../images/locked.png' width='45px' height='45px'></a></td><tr>
<?php	}				
		else
		{
?> 			<td align="center"><a href='targetLock.php?year=<?php echo $year;?>&month=<?php echo $row['month'];?>'><img src='../images/unlocked.png' width='45px' height='45px'></a></td><tr>			
<?php	}	
}																			?>	
</table>
</div>
</div>
</body>
</html>																		<?php
}
else
	header("Location:../index.php");
