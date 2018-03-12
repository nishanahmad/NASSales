<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';

	$mainArray = array();
	if(isset($_GET['year']) && isset($_GET['month']))
	{
		$year = (int)$_GET['year'];
		$month = (int)$_GET['month'];		
	}	
	else
	{
		$year = (int)date("Y");
		$month = (int)date("m");
	}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/loader.css">	
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script src="../js/fileSaver.js"></script>
<script src="../js/tableExport.js"></script>
<script type="text/javascript" language="javascript">
$(window).on('load', function() {
	$("#loader").hide();

 	$("#button").click(function(){
		$("table").tableExport({
				formats: ["xls"],    // (String[]), filetypes for the export
				bootstrap: false,
				ignoreCSS: ".ignore"   // (selector, selector[]), selector(s) to exclude from the exported file
		});
	});		
} );

function rerender()
{
	var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;

	var month=document.getElementById("jsMonth").value;

	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	$("#main").hide();
	$("#loader").show();
	window.location.href = hrf +"?year="+ year + "&month=" + month;
}
</script>

<title>ACC <?php echo getMonth($month); echo " "; echo $year; ?></title>
</head>
<body>
	<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<div class="circle"></div>
		<div class="circle1"></div>
		<br>
		<font style="color:white;font-weight:bold">Calculating ......</font>
	</div>
	<div align="center">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
		<br><br>
		<select id="jsMonth" name="jsMonth" class="textarea" onchange="return rerender();">	
			<option value = "<?php echo $month;?>"><?php echo getMonth($month);?></option>			<?php	
			$monthList = mysqli_query($con, "SELECT DISTINCT month FROM target WHERE month <> $month ORDER BY month ASC" ) or die(mysqli_error($con));	
			foreach($monthList as $monthObj) 
			{	
	?>			<option value="<?php echo $monthObj['month'];?>"><?php echo getMonth($monthObj['month']);?></option>		<?php	
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsYear" name="jsYear" class="textarea" onchange="return rerender();">
			<option value = "<?php echo $year;?>"><?php echo $year;?></option>			<?php	
			$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target  WHERE year <> $year ORDER BY year DESC") or die(mysqli_error($con));	
			foreach($yearList as $yearObj) 
			{
?>				<option value="<?php echo $yearObj['year'];?>"><?php echo $yearObj['year'];?></option>		<?php	
			}
?>		</select>
		<br><br>
		
		<img src="../images/excel.png" id="button" height="50px" width="45ypx" />
		<br/><br/>

		<table id="Points" class="responstable" style="width:65%">
			<tr>
				<th style="width:30%">AR</th>
				<th style="width:30%">Shop</th>
				<th style="width:12.5%">SAP</th>
				<th style="width:12.5%">Mobile</th>
				<th style="width:5%">ACC Target</th>
				<th style="width:5%">Sale</th>
				<th style="width:5%">Balance</th>	
			</tr>
							
<?php		$arObjects =  mysqli_query($con,"SELECT id,ar_name,mobile,shop_name,sap_code FROM ar_details WHERE  isActive = 1 ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
			foreach($arObjects as $ar)
			{
				$arMap[$ar['id']] = $ar['ar_name'];
				$mobileMap[$ar['id']] = $ar['mobile'];
				$shopMap[$ar['id']] = $ar['shop_name'];
				$sapMap[$ar['id']] = $ar['sap_code'];
			}			

			$targetList = mysqli_query($con,"SELECT ar_id, company_target FROM target WHERE  month = '$month' AND Year='$year' AND ar_id IN('".implode("','",array_keys($arMap))."')") or die(mysqli_error($con));		 

			foreach($targetList as $row)
			{
				$target = $row['company_target'];
				$arId = $row['ar_id'];

				$sales = mysqli_query($con,"SELECT SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM nas_sale WHERE '$year' = year(`entry_date`) 
															AND '$month' = month(`entry_date`)
															AND ar_id = '$arId'
															AND bill_no not like '%can%'")
															or die(mysqli_error($con));	

				$actual_sales = 0;

				foreach($sales as $sales_row)
				{
					// Assign clean variables
					$srp = $sales_row['SUM(srp)'];
					$srh = $sales_row['SUM(srh)'];
					$f2r = $sales_row['SUM(f2r)'];
					$return_bag = $sales_row['SUM(return_bag)'];
					$total = $srp + $srh + $f2r - $return_bag;


					$actual_sales = $actual_sales + $total;
				}	
?>
				<tr align="center">
				<td><?php echo $arMap[$arId];?></b></td>
				<td><?php echo $shopMap[$arId];?></b></td>
				<td><?php echo $sapMap[$arId];;?></b></td>
				<td><?php if(isset($mobileMap[$arId])) echo $mobileMap[$arId];?></b></td>
				<td><?php echo $target;?></td>
				<td><?php echo $actual_sales;?></td>
				<td><?php echo $target - $actual_sales;?></td>
				</tr>
<?php		}
?>
		</table>
		<br/><br/><br/><br/>
	</div>
</body>
</html>
<?php
}
else
	header("../Location:index.php");
?>