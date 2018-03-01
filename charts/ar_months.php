<?php 
require '../connect.php';
require '../library/array_push_assoc.php';
$ar = trim($_GET['ar']);

$mainArray = array();
$month6 = date('M');
$month5 = date("M", strtotime("-1 month"));
$month4 = date("M", strtotime("-2 month"));
$month3 = date("M", strtotime("-3 month"));
$month2 = date("M", strtotime("-4 month"));
$month1 = date("M", strtotime("-5 month"));
$mainArray = push($mainArray,$month1,0);
$mainArray = push($mainArray,$month2,0);
$mainArray = push($mainArray,$month3,0);
$mainArray = push($mainArray,$month4,0);
$mainArray = push($mainArray,$month5,0);
$mainArray = push($mainArray,$month6,0);


	if($_GET['ar'] != 'NAS')
	{
		$sales = mysqli_query($con,"SELECT * FROM sales_entry WHERE YEAR(entry_date) = YEAR(CURRENT_DATE) 
															AND MONTH(entry_date) >= MONTH(CURRENT_DATE - INTERVAL 6 MONTH) 
															AND ar = '$ar' 
															AND bill_no not like '%can%'")
															or die(mysqli_error($con));
	}
	
	else
		$sales = mysqli_query($con,"SELECT * FROM sales_entry WHERE YEAR(entry_date) = YEAR(CURRENT_DATE) 
															AND MONTH(entry_date) >= MONTH(CURRENT_DATE - INTERVAL 6 MONTH) 
															AND bill_no not like '%can%'")
															or die(mysqli_error($con));
	
	
	
	foreach($sales as $sales_row)
	{
	// Assign clean variables
		$lpp = $sales_row['lpp'];
		$hdpe = $sales_row['hdpe'];
		$cstl = $sales_row['cstl'];
		$return_bag = $sales_row['return_bag'];
		$total = $lpp + $hdpe + $cstl - $return_bag;
		$entry_month = date("M", strtotime($sales_row['entry_date']));
		
		if($entry_month == $month1)
			$mainArray[$month1] = $mainArray[$month1] + $total;
		if($entry_month == $month2)
			$mainArray[$month2] = $mainArray[$month2] + $total;
		if($entry_month == $month3)
			$mainArray[$month3] = $mainArray[$month3] + $total;
		if($entry_month == $month4)
			$mainArray[$month4] = $mainArray[$month4] + $total;
		if($entry_month == $month5)
			$mainArray[$month5] = $mainArray[$month5] + $total;
		if($entry_month == $month6)
			$mainArray[$month6] = $mainArray[$month6] + $total;
	}	
var_dump($mainArray);	
?>	
<html>
<head>
<title>Sales chart <?php echo $_GET['ar']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/table.css">		
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="highcharts.js"></script>
<script src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
</head>
<div align="center">

<select name="ar" id="ar" onchange="document.location.href = 'ar_months.php?ar=' + this.value">
	<option value = "NAS">NAS TOTAL</option>
    <?php
	$con = mysqli_connect("localhost","nishan","123","nas");
    $queryusers = "SELECT `ar_name` FROM `ar_details` ";
    $db = mysqli_query($con,$queryusers);
    while ( $d=mysqli_fetch_assoc($db)) 
	{
		if($_GET['ar'] == $d['ar_name'])
		{
?>			<option value="<?php echo $d['ar_name'];?>" selected><?php echo $d['ar_name'];?></option>			
<?php	}	
		else
		{
?>			<option value="<?php echo $d['ar_name'];?>" ><?php echo $d['ar_name'];?></option>			
<?php	}	
	}
    ?>
      </select>
	  
<h1><?php echo strtoupper($_GET['ar']); ?></h1>  
<div id="container" style="width:60%; height:500px;"></div>
<script>
$(function () {
    $('#container').highcharts({
        data: {
            table: 'datatable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Sales of the last 6 months'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Bags'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
});
</script>
<br><br><br><br><br>
<table id="datatable" class="table-2">
	<thead>
		<tr>
			<th>Month</th>
			<th>SALES</th>
		</tr>
	</thead>
	<tbody>
<?php
	
	foreach($mainArray as $month => $sales)
	{
?>		<tr>
			<td><?php echo $month; ?></td>
			<td><?php echo $sales; ?></td>
		</tr>
<?php
	}
?>

	</tbody>
</table>
</div>
</html>
