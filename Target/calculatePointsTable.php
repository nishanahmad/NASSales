<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require '../connect.php';
require '../library/array_push_assoc.php';

$mainArray = array();
$year = $_GET['year'];
$month = $_GET['month'];

// convert month to letter format for page display
$dateObj   = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F'); 
?>
<html>
<head>
<style>
.responstable {
  width: 80%;
  overflow: hidden;
  background: #FFF;
  color: #024457;
  border-radius: 10px;
  border: 1px solid #167F92;
}
.responstable tr {
  border: 1px solid #D9E4E6;
}
.responstable tr:nth-child(odd) {
  background-color: #EAF3F3;
}
.responstable th {
  display: none;
  border: 1px solid #FFF;
  background-color: #167F92;
  color: #FFF;
  padding: 1em;
}
.responstable th:first-child {
  display: table-cell;
  text-align: center;
}
.responstable th:nth-child(2) {
  display: table-cell;
}
.responstable th:nth-child(2) span {
  display: none;
}
.responstable th:nth-child(2):after {
  content: attr(data-th);
}
@media (min-width: 480px) {
  .responstable th:nth-child(2) span {
    display: block;
  }
  .responstable th:nth-child(2):after {
    display: none;
  }
}
.responstable td { 
  display: block;
  word-wrap: break-word;
  max-width: 3em;
}
.responstable td:first-child {
  display: table-cell;
  text-align: left;
  border-right: 1px solid #D9E4E6;
}
@media (min-width: 480px) {
  .responstable td {
    border: 1px solid #D9E4E6;
  }
}
.responstable th, .responstable td {
  text-align: center;
  margin: .5em 1em;
}
@media (min-width: 480px) {
  .responstable th, .responstable td {
    display: table-cell;
    padding: .3em;
  }
}

h1 {
  font-family: Verdana;
  font-weight: normal;
  color: #024457;
}
h1 span {
  color: #167F92;
}
</style>
<link rel="stylesheet" type="text/css" href="../css/loader.css">	
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
</script>
<script type="text/javascript">
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

<title><?php echo $monthName; echo " "; echo $year; ?></title>
</head>
<body>
<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
<br><br><br><br><br><br><br><br><br><br><br><br>
<div class="circle"></div>
<div class="circle1"></div>
<br>
<font style="color:white;font-weight:bold">Loading......</font>
</div>
<div id="main" class="main">

			<div class="tabcontents">
		
				<div id="view2">
					<div align="center" style="width:100%;">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
					<br><br>
					<select id="jsYear" name="jsYear" onchange="return rerender();">
						<option <?php if($year==2016) echo 'Selected';?> value="2016">2016</option>
						<option <?php if($year==2017) echo 'Selected';?> value="2017">2017</option>
						<option <?php if($year==2018) echo 'Selected';?> value="2018">2018</option>
						<option <?php if($year==2019) echo 'Selected';?> value="2019">2019</option>
						<option <?php if($year==2020) echo 'Selected';?> value="2020">2020</option>	
					</select>
					<select id="jsMonth" name="jsMonth" onchange="return rerender();">
						<option <?php if($month==1) echo 'Selected';?> value="1">January</option>
						<option <?php if($month==2) echo 'Selected';?> value="2">Februaury</option>
						<option <?php if($month==3) echo 'Selected';?> value="3">March</option>
						<option <?php if($month==4) echo 'Selected';?> value="4">April</option>
						<option <?php if($month==5) echo 'Selected';?> value="5">May</option>
						<option <?php if($month==6) echo 'Selected';?> value="6">June</option>
						<option <?php if($month==7) echo 'Selected';?> value="7">July</option>	
						<option <?php if($month==8) echo 'Selected';?> value="8">August</option>
						<option <?php if($month==9) echo 'Selected';?> value="9">September</option>
						<option <?php if($month==10) echo 'Selected';?> value="10">October</option>
						<option <?php if($month==11) echo 'Selected';?> value="11">November</option>
						<option <?php if($month==12) echo 'Selected';?> value="12">December</option>	
					</select>					
					<br><br>
					
					<img src="../images/excel.png" id="button" height="50px" width="45ypx" />
					<br/><br/>

					<table id="Points" class="responstable">
						<tr>
							<td style="width:200px"></td>
							<td style="width:90px">Mobile</td>
							<td>Target</td>
							<td>Actual Sales</td>
							<td>Rate</td>
							<td>Points</td>
							<td>Actual%</td>	
							<td>Point%</td>	
							<td>Payment%</td>	
							<td>Achieved Pnts</td>	
							<td>Payment Pnts</td>	
						</tr>
							
<?php						$arObjects =  mysqli_query($con,"SELECT id,ar_name,mobile FROM ar_details WHERE  isActive = 1 ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
							foreach($arObjects as $ar)
							{
								$arMap[$ar['id']] = $ar['ar_name'];
								$mobileMap[$ar['id']] = $ar['mobile'];
							}			

							$targetList = mysqli_query($con,"SELECT ar_id, target, payment_perc,rate FROM ar_calculation WHERE  month = '$month' AND Year='$year' AND ar_id IN('".implode("','",array_keys($arMap))."')") or die(mysqli_error($con));		 

							foreach($targetList as $row)
							{
								$target = $row['target'];
								$rate = $row['rate'];
								$arId = $row['ar_id'];

								$sales = mysqli_query($con,"SELECT SUM(srp),SUM(srh),SUM(f2r),SUM(return_bag) FROM sales_entry WHERE '$year' = year(`entry_date`) 
																			AND '$month' = month(`entry_date`)
																			AND ar_id = '$arId'
																			AND bill_no not like '%can%'")
																			or die(mysqli_error($con));	

								$actual_sales = 0;
								$points = 0;	
								$actual_perc = 0;
								$point_perc = 0;
								$payment_perc  = $row['payment_perc'];
								$achieved_points = 0;
								$payment_points = 0;

								foreach($sales as $sales_row)
								{
									// Assign clean variables
									$srp = $sales_row['SUM(srp)'];
									$srh = $sales_row['SUM(srh)'];
									$f2r = $sales_row['SUM(f2r)'];
									$return_bag = $sales_row['SUM(return_bag)'];
									$total = $srp + $srh + $f2r - $return_bag;


									$actual_sales = $actual_sales + $total;
									$points = round($actual_sales * $rate,0);


									if($target != 0)				$actual_perc = round($actual_sales * 100 / $target,0);
									else							$actual_perc = 0;
									
									if($year < 2017 || ($year == 2017 && $month <= 9))
									{
										if($actual_perc < 30)			$point_perc = 0;
										else if($actual_perc <= 40)		$point_perc = 20;
										else if($actual_perc <= 59)		$point_perc = 30;
										else if($actual_perc <= 69)		$point_perc = 40;
										else if($actual_perc <= 79)		$point_perc = 60;
										else if($actual_perc <= 89)		$point_perc = 80;
										else if($actual_perc <= 95)		$point_perc = 90;
										else if($actual_perc >= 96)		$point_perc = 100;										
									}
									else
									{
										if($actual_perc <= 70)			$point_perc = 0;
										else if($actual_perc <= 80)		$point_perc = 50;
										else if($actual_perc <= 95)		$point_perc = 70;
										else if($actual_perc >= 96)		$point_perc = 100;										
									}

									$achieved_points = round($points * $point_perc/100,0);
									$payment_points = round($achieved_points * $payment_perc/100,0);

								}	
								if($actual_sales <= 0 && $target >0)
									$payment_points = -50;
?>
								<tr align="center">
								<td><?php echo $arMap[$arId];?></b></td>
								<td><?php if(isset($mobileMap[$arId])) echo $mobileMap[$arId];?></b></td>
								<td><?php echo $target;?></td>
								<td><?php echo $actual_sales;?></td>
								<td><?php echo $rate;?></td>
								<td><?php echo $points;?></td>
								<td><?php echo $actual_perc;?></td>
								<td><?php echo $point_perc;?></td>
								<td><?php echo $payment_perc;?></td>
								<td><?php echo $achieved_points;?></td>
								<td><?php echo $payment_points;?></td>
								</tr>
<?php						}
?>
					</table>
					</div>
				</div>
	</div>
</div>
</body>
</html>
<?php
}
else
	header("../Location:loginPage.php");
?>