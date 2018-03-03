<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require '../connect.php';
require '../library/array_push_assoc.php';

$mainArray = array();

if(isset($_GET['fromDate']) && isset($_GET['toDate']))
{
	$fromDate = $_GET['fromDate'];
	$toDate = $_GET['toDate'];				
}
else
{
	$sqlDates = mysqli_query($con, "SELECT from_date,to_date FROM special_target_date ORDER BY to_date DESC LIMIT 1") or die(mysqli_error($con));		 
	$dates = mysqli_fetch_array($sqlDates,MYSQLI_ASSOC);
	$fromDate = $dates['from_date'];
	$toDate = $dates['to_date'];		
}	

$arList = mysqli_query($con,"SELECT id, ar_name, isActive FROM ar_details") or die(mysqli_error($con));		 
foreach($arList as $arObject)
{
	$activeMap[$arObject['id']] = $arObject['isActive'];
	$arNameMap[$arObject['id']] = $arObject['ar_name'];
}


$ar_detail = mysqli_query($con,"SELECT ar_id, special_target FROM target_special_target WHERE  fromDate <= '$fromDate' AND toDate>='$toDate'") or die(mysqli_error($con));		 

foreach($ar_detail as $ar)
{	
	$arId = $ar['ar_id'];
	if($activeMap[$arId])
	{
		$mainArray[$arId] = array();
		$mainArray[$arId]['special_target'] = $ar['special_target'];
		$mainArray[$arId]['actual_sales'] = 0;
		$mainArray[$arId]['percentage'] = 0;

		$sales = mysqli_query($con,"SELECT ar_id,SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM sales_entry WHERE entry_date >= '$fromDate'
																AND entry_date <= '$toDate'
																AND ar_id = '$arId'
																AND bill_no not like '%can%' GROUP BY ar_id")
																or die(mysqli_error($con));				

		foreach($sales as $sale)
		{
			$lpp = $sale['SUM(srp)'];
			$hdpe = $sale['SUM(srh)'];
			$cstl = $sale['SUM(f2r)'];
			$return_bag = $sale['SUM(return_bag)'];
			$total = $lpp + $hdpe + $cstl - $return_bag;
			
			$mainArray[$arId]['actual_sales'] = $mainArray[$arId]['actual_sales'] + $total;
			
			if($mainArray[$arId]['special_target'] != 0)
				$mainArray[$arId]['percentage'] = round($mainArray[$arId]['actual_sales'] * 100 / $mainArray[$arId]['special_target'],0);
			else
				$mainArray[$arId]['percentage'] = 0;
			
			
		}
	}
}
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
<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/dataTables.responsive.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="../js/dataTables.jqueryui.js"></script>
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

<title><?php //echo $monthName; echo " "; echo $year; ?></title>
</head>
<body>
			<div class="tabcontents">
		
				<div id="view2">
					<div align="center" style="width:100%;">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
					<h1>SPECIAL TARGET ACHIEVEMENT</h1>
					<br><br>
					<select onchange="javascript:location.href = this.value;">
						<?php						
						$queryDates = "SELECT from_date,to_date FROM special_target_date ORDER BY to_date ASC";
						$db = mysqli_query($con,$queryDates);
						while ( $row=mysqli_fetch_assoc($db)) 
						{
							$value = date('d-M-Y',strtotime($row['from_date'])).'&nbsp&nbsp&nbsp&nbspTO&nbsp&nbsp&nbsp&nbsp'.date('d-M-Y',strtotime($row['to_date']));									
							$urlValue = "achievementList.php?fromDate=".$row['from_date']."&toDate=".$row['to_date']."";									?>
						 <option <?php if($row['from_date'] == $fromDate) echo 'selected';?> value='<?php echo $urlValue;?>'><?php echo $value;?></option>   								<?php
						}
						?>
					</select>					
					<br><br>
					<table id="datatables" class="stripe hover order-column row-border compact" cellspacing="0" width="100%">
						<thead>
						<tr align="center">
						<th>AR</th>
						<th>Special Target</th>
						<th>Actual Sales</th>
						<th>Balance</th>
						<th>Achieved %</th>	
						</tr>
						</thead>

						<tbody>
						<?php
						foreach($mainArray as $arId =>$subarray)
						{
						?>
							<tr align="center">
							<td><?php echo $arNameMap[$arId];?></td>
							<td><?php echo $subarray['special_target']?></td>
							<td><?php echo $subarray['actual_sales']?></td>
							<td><?php echo $subarray['special_target']-$subarray['actual_sales']; ?></td>							
							<td><?php echo $subarray['percentage']?></td>
						<?php
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