<?php 
require '../connect.php';

$ar_detail = mysqli_query($con,"SELECT ar_name, target FROM ar_details WHERE ar_name like 'a%' ORDER BY ar_name") or die(mysqli_error($con));		 
foreach($ar_detail as $ar_row)
{
	$ar = $ar_row['ar_name'];
	
	$mainArray[$ar] = array();
	$mainArray[$ar]['ar'] = $ar;
	$mainArray[$ar]['target'] = $ar_row['target'];
	$mainArray[$ar]['actual_sales'] = 0;


	$sales = mysqli_query($con,"SELECT * FROM sales_entry WHERE YEAR(entry_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) 
															AND MONTH(entry_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
															AND ar = '$ar' 
															AND bill_no not like '%can%'")
								
															or die(mysqli_error($con));		 	
															
	foreach($sales as $sales_row)
	{
	// Assign clean variables
		$ar = $sales_row['ar'];
		$lpp = $sales_row['lpp'];
		$hdpe = $sales_row['hdpe'];
		$cstl = $sales_row['cstl'];
		$return_bag = $sales_row['return_bag'];
		$total = $lpp + $hdpe + $cstl - $return_bag;
		
		$mainArray[$ar]['actual_sales'] = $mainArray[$ar]['actual_sales'] + $total;
	}	
}
//var_dump($mainArray);	
?>	
<html>
<head>
<title>Monthly Performance Chart</title>
<link rel="stylesheet" type="text/css" href="../css/table.css">		
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="highcharts.js"></script>
<script src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
</head>
<div id="container" style="width:100%; height:800px;"></div>
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
            text: 'AR Performance Of The MONTH'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Units'
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
<br><br>
<div align="center">
<table id="datatable" class="table-2">
	<thead>
		<tr>
			<th>AR</th>
			<th>TARGET</th>
			<th>SALES</th>
		</tr>
	</thead>
	<tbody>
<?php
	
	foreach($mainArray as $key1 => $valuearray1)
	{
?>		<tr>
<?php
		foreach($valuearray1 as $key => $value)		
		{
?>				<td><?php echo $value; ?></td>
<?php	}	
?>		</tr>
<?php
	}
?>

	</tbody>
</table>

</html>
