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

<!DOCTYPE html>
<head>
	<link href='../css/bootstrap.min.css' rel='stylesheet' type='text/css'>
	<link href='../css/pointsForm.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">  

	<script src='../js/jquery.js' type='text/javascript'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.0/js/bootstrap.min.js' type='text/javascript'></script>
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
	
	
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#date" ).datepicker(pickerOpts);
	});		  
	</script>
</head>
<body>
  <div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>New Point Redemption</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form'>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' for='date'>Date</label>
            <div class='col-md-8'>
              <div class='col-md-3'>
                <div class='form-group internal input-group'>
                  <input class='form-control' id='date' name="date" required>
                </div>
              </div>
            </div>
          </div>		
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' for='ar'>AR</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <select class='form-control' id='ar' name="ar" required onchange="arRefresh();">
                    <option>AR1gsfrabsfkrdsgkdzkgbkdhdjfhbjedbhfkjdbsfksjb</option>
                    <option>AR2</option>
                    <option>AR3</option>
                  </select>
                </div>
              </div>
              <div class='col-md-4 indent-small'>
                <div class='form-group internal'>
                  <input class='form-control' id='shop' readonly placeholder='Shop' type='text'>
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' for='points'>Points</label>
            <div class='col-md-8'>
              <div class='col-md-3'>
                <div class='form-group internal input-group'>
                  <input class='form-control' id='points' name="points">
                </div>
              </div>
            </div>
          </div>		  
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' for='remarks'>Remarks</label>
            <div class='col-md-6'>
              <textarea class='form-control' id='remarks' name="remarks" placeholder='Remarks' rows='2'></textarea>
            </div>
          </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Redeem Points</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>																		<?php

}
else
	header("Location:../index.php");

