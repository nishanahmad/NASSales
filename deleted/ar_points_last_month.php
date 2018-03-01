<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require 'connect.php';
require 'library/array_push_assoc.php';

$mainArray = array();
$mainArray['Header'] = array('NAME','ID','TARGET','ACTUAL SALES','RATE','POINTS','ACTUAL%','POINT%','PAYMENT%','ACHIEVED PNTS','PAYMENT POINTS','DataLoader Check');
$ar_detail = mysqli_query($con,"SELECT ar_name,salesforce_id, target, payment_perc FROM ar_details WHERE salesforce_id NOT LIKE '%GENERAL%' ORDER BY ar_name") or die(mysqli_error($con));		 
foreach($ar_detail as $ar_row)
{
	$month = date('m');
	$month = $month -1;
	$year = date('Y');
	$ar = trim($ar_row['ar_name']);

	$target_query = mysqli_query($con,"SELECT target FROM target_details WHERE ar='$ar' AND
											  month = '$month' AND year='$year'  ") or die(mysqli_error($con));		 
	$target_deatails = mysqli_fetch_array($target_query,MYSQLI_ASSOC);
	$target = $target_deatails['target'];
	
	if($target == 0)
		$rate = 0;		
	else if($target<=550)
		$rate = 0.2;
	else if($target<=850)
		$rate = 0.3;
	else if($target<=1250)
		$rate = 0.4;
	else if($target<=2000)
		$rate = 0.5;
	else if($target>=2001)
		$rate = 0.6;
	
	$payment_perc = $ar_row['payment_perc'];
	$mainArray[$ar] = array();
	$mainArray[$ar]['ar'] = $ar;
	$mainArray[$ar]['salesforce_id'] = $ar_row['salesforce_id'];
	$mainArray[$ar]['target'] = $ar_row['target'];
	$mainArray[$ar]['actual_sales'] = 0;
	$mainArray[$ar]['rate'] = $rate;
	$mainArray[$ar]['points'] = 0;	
	$mainArray[$ar]['actual_perc'] = 0;
	$mainArray[$ar]['point_perc'] = 0;
	$mainArray[$ar]['payment_perc'] = 0;
	$mainArray[$ar]['achieved_points'] = 0;
	$mainArray[$ar]['payment_points'] = 0;
	$mainArray[$ar]['dataloader_check'] = 'true';

$sales = mysqli_query($con,"SELECT * FROM sales_entry WHERE YEAR(entry_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) 
															AND MONTH(entry_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
															AND ar = '$ar' 
															AND bill_no not like '%can%'")
								
															or die(mysqli_error($con));		 	
															
	foreach($sales as $sales_row)
	{
	// Assign clean variables
		$entry_date = date('d-m-Y',strtotime($sales_row['entry_date']));
		$ar = $sales_row['ar'];
		$ar = $sales_row['ar'];
		$lpp = $sales_row['lpp'];
		$hdpe = $sales_row['hdpe'];
		$cstl = $sales_row['cstl'];
		$return_bag = $sales_row['return_bag'];
		$total = $lpp + $hdpe + $cstl - $return_bag;
		
		$mainArray[$ar]['target'] = $target;
		$mainArray[$ar]['actual_sales'] = $mainArray[$ar]['actual_sales'] + $total;
		$mainArray[$ar]['rate'] = $rate;
		$mainArray[$ar]['points'] = round($mainArray[$ar]['actual_sales'] * $mainArray[$ar]['rate'],0);
		
		
		if($mainArray[$ar]['target'] != 0)
			$mainArray[$ar]['actual_perc'] = round($mainArray[$ar]['actual_sales'] * 100 / $mainArray[$ar]['target'],0);
		else
			$mainArray[$ar]['actual_perc'] = 0;
		
		if($mainArray[$ar]['actual_perc'] < 21)
			$mainArray[$ar]['point_perc'] = 0;
		else if($mainArray[$ar]['actual_perc'] <= 30)
			$mainArray[$ar]['point_perc'] = 10;
		else if($mainArray[$ar]['actual_perc'] <= 40)
			$mainArray[$ar]['point_perc'] = 20;
		else if($mainArray[$ar]['actual_perc'] <= 59)
			$mainArray[$ar]['point_perc'] = 40;
		else if($mainArray[$ar]['actual_perc'] <= 69)
			$mainArray[$ar]['point_perc'] = 60;
		else if($mainArray[$ar]['actual_perc'] <= 79)
			$mainArray[$ar]['point_perc'] = 70;
		else if($mainArray[$ar]['actual_perc'] <= 89)
			$mainArray[$ar]['point_perc'] = 80;
		else if($mainArray[$ar]['actual_perc'] <= 95)
			$mainArray[$ar]['point_perc'] = 90;		
		else if($mainArray[$ar]['actual_perc'] >= 96)
			$mainArray[$ar]['point_perc'] = 100;		
		
		$mainArray[$ar]['payment_perc'] = $payment_perc;
		$mainArray[$ar]['achieved_points'] = round($mainArray[$ar]['points'] * $mainArray[$ar]['point_perc']/100,0);
		$mainArray[$ar]['payment_points'] = round($mainArray[$ar]['achieved_points'] * $mainArray[$ar]['payment_perc']/100,0);
		
	}	
	
	if($mainArray[$ar]['actual_sales'] <= 0 && $target >0)
		$mainArray[$ar]['payment_points'] = -50;
}
var_dump($mainArray);
$_SESSION['mainArray'] = $mainArray;
?>
<html>
<head>
<style>
td.highlight {
        background-color : #FF6666 !important;
 }
h1{

/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#eeeeee+0,cccccc+100;Gren+3D */
background: #eeeeee; /* Old browsers */
background: -moz-linear-gradient(top,  #eeeeee 0%, #cccccc 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#eeeeee), color-stop(100%,#cccccc)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* IE10+ */
background: linear-gradient(to bottom,  #eeeeee 0%,#cccccc 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */
}


</style>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.jqueryui.js"></script>
<script type="text/javascript" language="javascript" >
$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#datatables').dataTable({
"scrollCollapse": true,
"paging":         false,
"responsive": true,
"bJQueryUI":true,
});

} );
</script>

<title>AR POINTS LAST MONTH</title>
</head>
<body>

				<div align="center" style="width:100%;">
					<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='50px' height='50px'/> </a>
					<h1>AR POINTS LAST MONTH</h1>
					<br>
					
<!------------------------------------------------ 		DOWNLOAD BUTTON		---------------------------------------------->

				<form name="csv" method="post" action="download_points_last_month.php" >
				<div align="center"><input type="submit" name="submit" value="Download" ></div></td>
				
<!----------------------------------------------------------------------------------------------------------------------->				
					
					<br>
					
					<table id="datatables" class="stripe hover order-column row-border compact" cellspacing="0" width="100%">
						<thead>
						<tr align="center">
						<th>AR</th>
						<th>Target</th>
						<th>Actual Sales</th>
						<th>Rate</th>
						<th>Points</th>
						<th>Actual%</th>	
						<th>Point%</th>	
						<th>Payment%</th>	
						<th>Achieved Pnts</th>	
						<th>Payment Pnts</th>	
						</tr>
						</thead>

						<tbody>
						<?php
						foreach($mainArray as $ar =>$subarray)
						{
							if($ar != 'Header')
							{
?>								<tr align="center">
								<td><?php echo $ar;?></td>
								<td><?php echo $subarray['target']?></td>
								<td><?php echo $subarray['actual_sales']?></td>
								<td><?php echo $subarray['rate']?></td>
								<td><?php echo $subarray['points']?></td>
								<td><?php echo $subarray['actual_perc']?></td>
								<td><?php echo $subarray['point_perc']?></td>
								<td><?php echo $subarray['payment_perc']?></td>
								<td><?php echo $subarray['achieved_points']?></td>
								<td><?php echo $subarray['payment_points']?></td>
<?php						}	

						}
						?>
						</tbody>
					</table>
					</div>
				</div>
				



</body>
</html>
<?php
}

else
	header("Location:loginPage.php");

?>