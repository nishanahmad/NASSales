<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
?>

<html>
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
<head>
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
{																				?>	
	<div class='toast' style="display:none;">Update Succesfull !</div><br>		<?php
}																				?>
<form name="arBulkUpdate" method="post" action="updateServerArList.php">
<table align="center" class="responstable">
<?php
$areaObjects = mysqli_query($con, "SELECT area, number FROM area order by number asc ") or die(mysqli_error($con));			 
foreach($areaObjects as $areaObject) 
{
	$area = $areaObject['area'];
	$arObjects = mysqli_query($con,"SELECT id,ar_name, mobile, area, isActive FROM ar_details WHERE area= '$area' ORDER BY ar_name ASC ");
	foreach($arObjects as $ar)
	{
		$arName = $ar['ar_name'];
		$mobile = $ar['mobile'];
		$status = $ar['isActive'];								?>	
	<tr>
		<td style="width:30%"><label align="center"><?php echo $arName; ?></td>	
		<td style="text-align:center;width:15%"><input type="text" style="text-align:center;width:80px;border:0px;background-color: transparent;" name="<?php echo $arName.'-mobile';?>" value="<?php echo $mobile; ?>"></td>		
		<td style="text-align:center;">
			<select  style="text-align:center;width:270px;border:0px;background-color: transparent;" name="<?php echo $arName.'-area';?>" >
			<option value="<?php echo $area; ?>"><?php echo $area; ?></option>						<?php
			$areaList = mysqli_query($con, "SELECT area FROM area WHERE area <> '$area' ORDER BY area asc ") or die(mysqli_error($con));			 
			foreach($areaList as $row)
			{																						?>		
				<option value="<?php echo $row['area'];?>"><?php echo $row['area'];?></option>   	<?php
			}																						?>
			</select>	
		</td>	
		<td style="text-align:center;width:15%">
			<select  style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arName.'-status';?>">
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
</html>
<?php
}
else
	header("Location:../index.php");
?>