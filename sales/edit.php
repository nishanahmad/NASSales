<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(isset($_SESSION["user_name"]))
{
require '../connect.php';
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

$arObjects = mysqli_query($con,"SELECT id,ar_name FROM ar_details") or die(mysqli_error($con));	
foreach($arObjects as $ar)
{
	$arMap[$ar['id']] = $ar['ar_name']; 
}

if(count($_POST)>0) 
{	
	$originalDate = $_POST["entryDate"];
	$newDate = date("Y-m-d", strtotime($originalDate)); 

	$result1 = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	$row1= mysqli_fetch_array($result1,MYSQLI_ASSOC);
	
	if ($row1["entry_date"] != $newDate)
	{   
		$entry_date_dt = date('Y-m-d H:i:s'); 
		$entry_date_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET entry_date_mod='$entry_date_mod', entry_date_dt='$entry_date_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}	


	if ($row1["truck_no"] != $_POST["truck"])
	{
		$truck_no_dt = date('Y-m-d H:i:s'); 
		$truck_no_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET truck_no_mod='$truck_no_mod', truck_no_dt='$truck_no_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["srp"] != $_POST["srp"])
	{
		$srp_dt = date('Y-m-d H:i:s'); 
		$srp_mod = $_SESSION["user_name"];
		$total = $_POST["srp"] - $row1["srp"];
		
		$query = mysqli_query($con,"UPDATE nas_sale SET srp_mod='$srp_mod', srp_dt='$srp_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["srh"] != $_POST["srh"])
	{
		$srh_dt = date('Y-m-d H:i:s'); 
		$srh_mod = $_SESSION["user_name"];
		$total = $_POST["srh"] - $row1["srh"];

		$query = mysqli_query($con,"UPDATE nas_sale SET srh_mod='$srh_mod', srh_dt='$srh_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["f2r"] != $_POST["f2r"])
	{
		$f2r_dt = date('Y-m-d H:i:s'); 
		$f2r_mod = $_SESSION["user_name"];
		$f2r_mod = $_SESSION["user_name"];
		$total = $_POST["f2r"] - $row1["f2r"];

		$query = mysqli_query($con,"UPDATE nas_sale SET f2r_mod='$f2r_mod', f2r_dt='$f2r_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["remarks"] != $_POST["remarks"])
	{
		$remarks_dt = date('Y-m-d H:i:s'); 
		$remarks_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET remarks_mod='$remarks_mod', remarks_dt='$remarks_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["bill_no"] != $_POST["bill"])
	{
		$bill_no_dt = date('Y-m-d H:i:s'); 
		$bill_no_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET bill_no_mod='$bill_no_mod', bill_no_dt='$bill_no_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["customer_name"] != $_POST["customerName"])
	{
		$customer_name_dt = date('Y-m-d H:i:s'); 
		$customer_name_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET customer_name_mod='$customer_name_mod', customer_name_dt='$customer_name_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["customer_phone"] != $_POST["customerPhone"])
	{
		$customer_phone_dt = date('Y-m-d H:i:s'); 
		$customer_phone_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET customer_phone_mod='$customer_phone_mod', customer_phone_dt='$customer_phone_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["address1"] != $_POST["address1"])
	{
		$address1_dt = date('Y-m-d H:i:s');
		$address1_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET address1_mod='$address1_mod', address1_dt='$address1_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}

	if ($row1["address2"] != $_POST["address2"])
	{
		$address2_dt = date('Y-m-d H:i:s');
		$address2_mod = $_SESSION["user_name"];
		$query = mysqli_query($con,"UPDATE nas_sale SET address2_mod='$address2_mod', address2_dt='$address2_dt'
							        WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
	}
	
	
	
	
	$query = mysqli_query($con,"UPDATE nas_sale SET entry_date='$newDate', ar_id='" . $_POST["ar"] . "', truck_no='" . $_POST["truck"] . "',
								srp='" . $_POST["srp"] . "', srh='" . $_POST["srh"] . "' , f2r='" . $_POST["f2r"] . "',
								remarks='" . $_POST["remarks"] . "', bill_no='" . $_POST["bill"] . "', address1='" . $_POST["address1"] . "', 
								address2='" . $_POST["address2"] . "', customer_name='" . $_POST["customerName"] . "', return_bag='".$_POST["return"]."',
								customer_phone='" . $_POST["customerPhone"] . "'
								WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	

		  
	if($_GET['clicked_from'] == 'all_sales')	
		$url = 'list.php';
	else	
		$url = 'todayList.php?ar=all';

	header( "Location: $url" );
}

$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));	
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<html>
<head>
<title>Edit Sale <?php echo $row['sales_id']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
<link rel="stylesheet" href="../css/button.css">
</head>
<body>
<form name="frmUser" method="post" action="">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="todayList.php?ar=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
	<a href="../modified_by.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" >
		<img align="right" alt= 'Modified By' title='Modified By' src='../images/user.png' width='40px' height='50px'hspace='10'  /></a>
		
</div>

<br>
<div align ="center">
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4" style="text-align:center;"><b><font size="4">Edit Sale <?php echo $row['sales_id']; ?> </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="text" name="entryDate" class="txtField" 
	value="<?php 
			$originalDate1 = $row['entry_date'];
			$newDate1 = date("d-m-Y", strtotime($originalDate1));
			echo $newDate1; ?>">
</td>

<td><label>Remarks</label></td>
<td><input type="text" name="remarks" class="txtField" value="<?php echo $row['remarks']; ?>"></td>


</tr>
<tr>
<td><label>AR</label></td>
<td><select name="ar" required class="txtField">
    <option value = "<?php echo $row['ar_id'];?>"><?php echo $arMap[$row['ar_id']];?></option>
    <?php
		foreach($arMap as $arId => $arName)
		{?>
			<option value="<?php echo $arId;?>"><?php echo $arName;?></option>			
<?php	}
?>
      </select>
</td>

<td><label>Bill No </label></td>
<td><input type="text" name="bill" class="txtField" value="<?php echo $row['bill_no']; ?>"></td>
</tr>

<td><label>Truck No </label></td>
<td><input type="text" name="truck" class="txtField" value="<?php echo $row['truck_no']; ?>"></td>


<td><label>Customer Name</label></td>
<td><input type="text" name="customerName" class="txtField" value="<?php echo $row['customer_name']; ?>"></td>
</tr>

<td><label>SRP</label></td>
<td><input type="text" name="srp" class="txtField" value="<?php $srp = $row['srp'];echo $row['srp'];?>">
</td>

<td><label>Address Part 1</label></td>
<td><input type="text" name="address1" class="txtField" value="<?php echo $row['address1']; ?>"></td>
</tr>

<td><label>SRH</label></td>

<td><input type="text" name="srh" class="txtField" value="<?php echo $row['srh']; ?>"></td>

<td><label>Address Part 2</label></td>
<td><input type="text" name="address2" class="txtField" value="<?php echo $row['address2']; ?>"></td>
</tr>

<td><label>F2R</label></td>
<td><input type="text" name="f2r" class="txtField" value="<?php echo $row['f2r']; ?>"></td>


<td><label>Customer Phone</label></td>
<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $row['customer_phone']; ?>"></td>
</tr>

<td><label>Return</label></td>
<td><input type="text" name="return" class="txtField" value="<?php echo $row['return_bag']; ?>"></td>

<tr>
<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>

</table>
<br><br><br><br>	
<a href="delete.php?sales_id=<?php echo $row['sales_id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
</div>
</form>
</body>
</html>

<?php
}
else
header("../Location:loginPage.php");
?>