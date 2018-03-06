<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
// Populate maps for SAP CODE and SHOP NAME
	$arObjects = mysqli_query($con,"SELECT id,ar_name,sap_code,shop_name FROM ar_details");
	foreach($arObjects as $arObject)
	{
		$arId = $arObject['id'];
		
		$shopName = strip_tags($arObject['shop_name']);
		$shopNameMap[$arId] = $shopName;
	}
	
	$shopNameArray = json_encode($shopNameMap);
	$shopNameArray = str_replace('\n',' ',$shopNameArray);
	$shopNameArray = str_replace('\r',' ',$shopNameArray);	
?>

<html>
<head>
	<title>NEW SALE</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
	<script>
	var shopNameList = '<?php echo $shopNameArray;?>';
	var shopName_array = JSON.parse(shopNameList);
	var shopNameArray = shopName_array;									

	function arRefresh()
	{
		var arId = $('#ar').val();
		var shopName = shopNameArray[arId];
		$('#shopName').val(shopName);
	}								

	function validateForm() 
	{
		var srp = parseInt(document.forms["frmUser"]["srp"].value);
		var srh = parseInt(document.forms["frmUser"]["srh"].value);
		var f2r = parseInt(document.forms["frmUser"]["f2r"].value);

		if ( !srp && !srh && !f2r )
		{
			alert("Please enter a value in atleast one of these fields : srp,srh,F2R");
			return false;
		}	
	}
	
	$(function() {

	var pickerOpts = { dateFormat:"dd-mm-yy"}; 
				
	$( "#datepicker" ).datepicker(pickerOpts);

	});
	
	</script>
</head>
<body>
	<form name="frmUser" method="post" action="insert.php" onsubmit="return validateForm()">
		<div align="center" style="padding-bottom:5px;">
			<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
			<a href="todayList.php?ar=all" class="link">
			<img alt='List' title='List Sales' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
		</div>
		<br>
		<table border="0" cellpadding="15" cellspacing="0" width="80%" align="center" style="float:center" class="tblSaveForm">
			<tr class="tableheader">
				<td colspan="4"><div align ="center"><b><font size="4">NEW SALE</font><b></td>
			</tr>

			<tr>
				<td><label>Date</label></td>
				<td><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>

				<td><label>Remarks</label></td>
				<td><input type="text" name="remarks" class="txtField"></td>
			</tr>

			<tr>
				<td><label>AR</label></td>
				<td><select name="ar" id="ar" required class="txtField" onChange="arRefresh();">
					<option value = "">---Select---</option>
					<?php
					foreach($arObjects as $ar) 
					{
				?>		<option value="<?php echo $ar['id'];?>"><?php echo $ar['ar_name'];?></option>
				<?php	
					}
				?>
					  </select>
				</td>

				<td><label>Bill No</label></td>
				<td><input type="text" name="bill" class="txtField"></td>
			</tr>

			<tr>
				<td><label>Truck no</label></td>
				<td><input type="text" name="truck" class="txtField"></td>

				<td><label>Customer Name</label></td>
				<td><input type="text" name="customerName" class="txtField"></td>
			</tr>

			<tr>
				<td><label>SRP</label></td>
				<td><input type="text" name="srp" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>

				<td><label>Address Part 1</label></td>
				<td><input type="text" name="address1" class="txtField"></td>
			</tr>

			<tr>
				<td><label>SRH</label></td>
				<td><input type="text" name="srh" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>

				<td><label>Address Part 2</label></td>
				<td><input type="text" name="address2" class="txtField"></td>
			</tr>

			<tr>
				<td><label>F2R</label></td>
				<td><input type="text" name="f2r" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>

				<td><label>Customer Phone</label></td>
				<td><input type="text" name="customerPhone" class="txtField"></td>
			</tr>
			
			<tr>
				<td><label>Return</label></td>
				<td><input type="text" name="return" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>
				
				<td><label>Shop</label></td>
				<td><input type="text" readonly name="shopName" id="shopName" class="txtField"></td>	
			</tr>
			
			</tr>
			<tr>
			<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
			</tr>
		</table>
		</div>
	</form>

	<br>
</body>
</html>

<?php
}
else
	header("../Location:index.php");
?>