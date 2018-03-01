<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$queryArs = "SELECT sap_code,ar_name FROM ar_details WHERE sap_code IS NOT NULL ORDER BY ar_name ASC";
    $arObjects = mysqli_query($con,$queryArs);	
	
// Populate maps for SAP CODE and SHOP NAME
	$query = mysqli_query($con,"SELECT ar_name,sap_code,shop_name FROM ar_details");
	while($row= mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$ar = $row['ar_name'];
		$sapCode = strip_tags($row['sap_code']);
		$shopName = strip_tags($row['shop_name']);

		if(!empty($sapCode))
		{
			$arNameMap[$sapCode] = $ar;
			$shopNameMap[$sapCode] = $shopName;			
		}
	}
	$arNameArray = json_encode($arNameMap);
	$arNameArray = str_replace('\n',' ',$arNameArray);
	$arNameArray = str_replace('\r',' ',$arNameArray);		
	
	$shopNameArray = json_encode($shopNameMap);
	$shopNameArray = str_replace('\n',' ',$shopNameArray);
	$shopNameArray = str_replace('\r',' ',$shopNameArray);	
?>

<html>
<head>
<title>COMPANY SALE</title>
<script>
var arNameList = '<?php echo $arNameArray;?>';
var arName_array = JSON.parse(arNameList);
var arNameArray = arName_array;									

var shopNameList = '<?php echo $shopNameArray;?>';
var shopName_array = JSON.parse(shopNameList);
var shopNameArray = shopName_array;									

function arRefresh()
{
	var sapCode = $('#ar').val();
	console.log(sapCode);
	var arName = arNameArray[sapCode];
	var shopName = shopNameArray[sapCode];
	$("#arName").text(arName);
	$('#shopName').text(shopName);
	console.log(arName);
	console.log(shopName);
}								
</script>
<script>
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
</script>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/companySale.css" />
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>

<script>
$(function() {

var pickerOpts = { dateFormat:"dd-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>

</head>
<body>
<form name="frmUser" method="post" action="insert.php" onsubmit="return validateForm()">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="0" cellpadding="15" cellspacing="0" width=50%" align="center" style="float:center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">COMPANY SALE ENTRY </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td colspan="3"><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>
</tr>
<tr>
<td><label>AR</label></td>
<td><select name="ar" id="ar" required class="txtField" onChange="arRefresh();">
    <option value = "">---Select---</option>
    <?php   
    while ( $ar=mysqli_fetch_assoc($arObjects)) 
	{
?>		<option value="<?php echo $ar['sap_code'];?>"><?php echo $ar['sap_code'];?></option>			<?php
	}
?>
      </select>
</td>
</tr>

<tr>
<td><label>SRP</label></td>
<td colspan="3"><input type="text" name="srp" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>
</tr>
<tr>
<td><label>SRH</label></td>
<td colspan="3"><input type="text" name="srh" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>
</tr>
<tr>
<td><label>F2R</label></td>
<td colspan="3"><input type="text" name="f2r" class="txtField" pattern="[0-9]+" title="Input a valid number"></td>
</tr>
<tr>
<td><label>Remarks</label></td>
<td colspan="3"><input type="text" name="remarks" class="txtField"></td>
</tr>

<tr>
<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
</tr>

</table>

<table border="0" cellpadding="15" cellspacing="0" width=50%" align="center" style="float:center">
<tr>
	<td id="arName"/>
</tr>
<tr>
	<td id="shopName"/>
</tr>	
</table>

</div>
</form>
</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>