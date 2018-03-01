<?php 
require '../connect.php';
require '../library/array_push_assoc.php';

$mainArray = array();
$month6 = date('M');
$month5 = date("M", strtotime("-1 month"));
$month4 = date("M", strtotime("-2 month"));
$month3 = date("M", strtotime("-3 month"));
$month2 = date("M", strtotime("-4 month"));
$month1 = date("M", strtotime("-5 month"));
$mainArray[$month1] = array();
$mainArray[$month2] = array();
$mainArray[$month3] = array();
$mainArray[$month4] = array();
$mainArray[$month5] = array();
$mainArray[$month6] = array();

$sales = mysqli_query($con,"SELECT * FROM sales_entry WHERE YEAR(entry_date) = YEAR(CURRENT_DATE) 
															AND MONTH(entry_date) >= MONTH(CURRENT_DATE - INTERVAL 6 MONTH) 
															AND (ar like '%PUTHIYATHERU%'
															OR ar like '%VELLOZHUCKE%'
															OR ar like '%VATTAPOYIL%')															
															AND bill_no not like '%can%'")
															or die(mysqli_error($con));
	
	foreach($sales as $sales_row)
	{
		$ar = trim($sales_row['ar']);

		$mainArray[$month1] = push($mainArray[$month1],$ar,0);
		$mainArray[$month2] = push($mainArray[$month2],$ar,0);
		$mainArray[$month3] = push($mainArray[$month3],$ar,0);
		$mainArray[$month4] = push($mainArray[$month4],$ar,0);
		$mainArray[$month5] = push($mainArray[$month5],$ar,0);
		$mainArray[$month6] = push($mainArray[$month6],$ar,0);
	}
	
//var_dump($mainArray);	
	foreach($sales as $sales_row)
	{
	// Assign clean variables
		$ar = trim($sales_row['ar']);
		$lpp = $sales_row['lpp'];
		$hdpe = $sales_row['hdpe'];
		$cstl = $sales_row['cstl'];
		$return_bag = $sales_row['return_bag'];
		$total = $lpp + $hdpe + $cstl - $return_bag;
		$entry_month = date("M", strtotime($sales_row['entry_date']));	
		
		if($entry_month == $month1)
			$mainArray[$month1][$ar] = $mainArray[$month1][$ar] + $total;
		if($entry_month == $month2)
			$mainArray[$month2][$ar] = $mainArray[$month2][$ar] + $total;
		if($entry_month == $month3)
			$mainArray[$month3][$ar] = $mainArray[$month3][$ar] + $total;
		if($entry_month == $month4)
			$mainArray[$month4][$ar] = $mainArray[$month4][$ar] + $total;
		if($entry_month == $month5)
			$mainArray[$month5][$ar] = $mainArray[$month5][$ar] + $total;
		if($entry_month == $month6)
			$mainArray[$month6][$ar] = $mainArray[$month6][$ar] + $total;
	}	
//var_dump($mainArray);	
?>	
<html>
<head>
<title>Sales chart</title>
<link rel="stylesheet" type="text/css" href="../css/table.css">		
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="highcharts.js"></script>
<script src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
</head>
<div align="center">

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
		        xAxis: {
            categories: [
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug'
               
            ],
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
			
			<th>AR</th>
			<th>SALES</th>
		</tr>
	</thead>
	<tbody>
<?php
	
	foreach($mainArray as $month => $arr1)
	{
		foreach($arr1 as $ar => $sales)
		{
?>		<tr>
			
			<td><?php echo $ar; ?></td>
			<td><?php echo $sales; ?></td>
		</tr>		
<?php	}	
	}
?>

	</tbody>
</table>
</div>
</html>
