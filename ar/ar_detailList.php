<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$sqlArea = "SELECT area, number FROM area order by number asc ";
	$resultArea = mysqli_query($con, $sqlArea) or die(mysqli_error($con));			 
?>

<html>
<head>
<style>
.toast {
    width:25%;
    height:auto;
    left:45%;
	margin: 0 auto;
	bottom:10px;
    background-color: #328e30;
    color: #F0F0F0;
    font-family: "Times New Roman", Times, serif;
	padding:40px;
    font-size: 20px;
    padding:10px;
    text-align:center;
    border-radius: 2px;
    -webkit-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
    -moz-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
    box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/loader.css">
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<title>AR List</title>
<script type="text/javascript" language="javascript" >
$(document).ready(function() {
	$('.toast').fadeIn(500).delay(2000).fadeOut(1000); 
});
</script>
</head>
<body>
<div id="main" class="main">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
</div>
<?php
if(isset($_GET['message']))
{
?>	
<div class='toast' style="display:none;">Update Succesfull !</div>	
<br>
<?php
}	
?>
<form name="arBulkUpdate" method="post" action="updateServerArList.php">
<table align="center" class="responstable" style="width:60%;">
<?php
while($rowArea = mysqli_fetch_array($resultArea,MYSQLI_ASSOC)) 
{	
	$area = $rowArea['area'];
	$sql = "SELECT ar_name, mobile, area, isActive FROM ar_details WHERE area='".$area."' order by ar_name asc ";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));?>
	<tr><th colspan="4"><?php if($area != null) echo $area; else echo 'NO AREA SPECIFIED'?></th></tr>
	<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$arname = $row['ar_name'];
		$area = $row['area'];
		$mobile = $row['mobile'];
		$status = $row['isActive'];
	?>	
	<tr>
	<td style="width:30%"><label align="center"><?php echo $arname; ?></td>	
	<td style="text-align:center;width:15%"><input type="text" style="text-align:center;width:80px;border:0px;background-color: transparent;" name="<?php echo $arname.'-mobile';?>" value="<?php echo $mobile; ?>"></td>		
	<td style="text-align:center;">
	<select  style="text-align:center;width:270px;border:0px;background-color: transparent;" name="<?php echo $arname.'-area';?>" >
	<option value="<?php echo $area; ?>"><?php echo $area; ?></option>
	<?php
    $queryusers = "SELECT `area` FROM `area` order by number asc";
    $db = mysqli_query($con,$queryusers);
    while ( $d=mysqli_fetch_assoc($db)) {
		echo "<option value='".$d['area']."'>".$d['area']."</option>";    }
    ?>
    </select>	
	</td>	
	<td style="text-align:center;width:15%">
	<select  style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arname.'-status';?>">
	<option <?php if($status == '1') echo 'selected';?> value = "1">Active</option>
	<option <?php if($status == '0') echo 'selected';?> value = "0">InActive</option>
	</td>
	</tr>																												<?php
	}						
}																								?>
</table>
<br><br>
<div align="center"><input type="submit" name="submit" value="Submit" onclick=" return showLoader()"></div>		
</form>
</div>
</body>
</html>																														<?php

}
else
	header("Location:../index.php");
