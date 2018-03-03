<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$arObjects = mysqli_query($con,"SELECT id,ar_name FROM ar_details ORDER BY ar_name ASC") or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['ar_name']; 
	}

	if($_GET['ar'] != 'all')
		$result = mysqli_query($con,"SELECT sales_id, entry_date,ar_id,truck_no,srp,srh,f2r,remarks, bill_no, customer_name, customer_phone, address1, address2 FROM nas_sale WHERE ar_id='" . $_GET['ar'] . "' and entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
	else
		$result = mysqli_query($con,"SELECT sales_id, entry_date,ar_id,truck_no,srp,srh,f2r,remarks, bill_no, customer_name, customer_phone, address1, address2 FROM nas_sale WHERE entry_date = CURDATE() order by bill_no asc  ") or die(mysqli_error($con));
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<title>Sales List</title>
<link rel="stylesheet" type="text/css" href="../css/styles.css" />
</head>
<body>
<form name="frmsales" method="post" action="" >
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
</div>
<br>
<div align="center">
<select name="ar" id="ar" onchange="document.location.href = 'todayList.php?ar=' + this.value" class="txtField">
    <option value = "">--SELECT--</option>
	<option value = "all">ALL</option>
    <?php
	foreach($arMap as $arId => $arName)
	{
		echo '<option value="'.$arId.'">'.$arName.'</option>'; 
	}
		
    ?>
      </select>
	  
<h3> Date :  <?php echo date("d-m-Y") ?></h3>	  
</div>	  

<br>
<div align="center"><table width="98%" class="table-responsive">
<tr class="tableheader">
<th>AR</th>
<th>TRUCK NO</th>
<th width="50px">SRP</th>
<th width="50px;">SRH</th>
<th width="50px;">F2R</th>
<th>BILL NO</th>
<th>CUST. NAME</th>
<th>CUST. PHONE</th>
<th>REMARKS</th>
<th>ADDRESS1</th>
<th>ADDRESS2</th>
</tr>
<?php
	$f2r=0;
	$srp=0;
	$srh=0;
	$total=0;
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$f2r = $f2r + $row["f2r"];
		$srp = $srp + $row["srp"];
		$srh = $srh + $row["srh"];
?>
<tr>
<td ><a href="edit.php?sales_id=<?php echo $row['sales_id'];?>"</a><?php echo $arMap[$row["ar_id"]]; ?></td>
<td><?php echo $row["truck_no"]; ?></td>
<td align="center"><?php echo $row["srp"]; ?></td>
<td align="center"><?php echo $row["srh"]; ?></td>
<td align="center"><?php echo $row["f2r"]; ?></td>
<td><?php echo $row["bill_no"]; ?></td>
<td><?php echo $row["customer_name"]; ?></td>
<td><?php echo $row["customer_phone"]; ?></td>
<td><?php echo $row["remarks"]; ?></td>
<td><?php echo $row["address1"]; ?></td>
<td><?php echo $row["address2"]; ?></td>
</tr>
<?php
	}
	$total = $total + $f2r + $srp + $srh;
	echo "<div align ='center' style ='font:20px bold;color:#000000'> SRP = $srp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp F2R = $f2r &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp SRH = $srh </div>";
	echo "<br>";
	echo "<div align ='center' style ='font:20px bold;color:#000000'> TOTAL = $total </div>";
	
	
	

	
	?>
</table>
</form>
<br><br><br>
<form name="csv" method="post" action="../today_sales_csv.php" >
<div align="center"><input type="submit" name="submit" value="Download" ></div></td>

</div>
    <script src="https://code.jquery.com/jquery.js"></script>
	<script src="../bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
</body></html>

<?php
}

else
	header("../Location:loginPage.php");

?>
