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
	<title>Redeem Points</title>
	<link href='../css/bootstrap.min.css' rel='stylesheet' type='text/css'>
	<link href='../css/pointsForm.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css"> 
	<link rel="stylesheet" type="text/css" href="../css/toast.css">	

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
		$('#shop').val(shopName);
	}									
	
	
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#date" ).datepicker(pickerOpts);
	});		  
	

	$(document).ready(function() {
		$('.toast').fadeIn(500).delay(2000).fadeOut(1000); 
	});

	</script>
</head>
<body>
  <br><br>
<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>  
  <br><br>
  <div class='container' style="width:60%;align:center;">																																								<?php
	if(isset($_GET['success']))
	{																																													?>	
		<div class='toast' style="display:none;">SUCCESS!!!</div><br>																											<?php
	}																																													?>	      
	<div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>New Point Redemption</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="insert.php" method="post">
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
					<option value = "">---Select---</option>																															<?php
					foreach($arObjects as $ar) 
					{																																									?>
						<option value="<?php echo $ar['id'];?>"><?php echo $ar['ar_name'];?></option>																					<?php	
					}																																									?>
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
                  <input class='form-control' id='points' required name="points" pattern="[0-9]+" title="Input a valid number">
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
		  <br><br>
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
</html>																																													<?php

}
else
	header("Location:../index.php");

