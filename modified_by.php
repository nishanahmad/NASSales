<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require 'connect.php';

$result = mysqli_query($con,"SELECT * FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));				 
		 
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<html>

<head>

  <meta charset="UTF-8">

  <title>Last Modified Information</title>
<div align="center" style="padding-bottom:5px;">
	<a href="index.php" class="link"><img alt='Home' title='Home' src='images/homeBlack.png' width='60px' height='60px'/></a>
	<a href="editSales.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" >
		<img alt= 'Edit' title='Edit' src='images/editblack.png' width='60px' height='60px'hspace='10'  /></a>
	<a href="salesToday.php" class="link"><img alt='List' title='List' src='images/list_icon.jpg' width='50px' height='50px'/></a><b><br><br>
	<b>Record created by <?php echo "<font color='red'>".$row['entered_by']."</font>"; ?> on <?php
			$currentDateTime = $row['entered_on'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?>
	 

  <style>
/*! normalize.css v3.0.2 | MIT License | git.io/normalize */html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background-color:transparent}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:bold}dfn{font-style:italic}h1{font-size:2em;margin:0.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-0.5em}sub{bottom:-0.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace, monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type="button"],input[type="reset"],input[type="submit"]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type="checkbox"],input[type="radio"]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0}input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button{height:auto}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:0.35em 0.625em 0.75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:bold}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}

</style>

    <style>
@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,600,700);
html {
    background: url(http://upload.robinbrons.com/u/1362757499.png);
}
body {
  font-family: 'Open Sans', sans-serif;
  font-weight: 400;
}
.event {
  width: 300px;
  height: 80px;
  background: #fff;
  border: 1px solid #CCC;
  border-radius: 2px;
  margin: 50px;
}
.event:before {
  content: '';
  display: block;
  width: 295px;
  height: 70px;
  background: #fff;
  border: 1px solid #CCC;
  border-radius: 2px; 
  transform: rotate(2deg);
  position: relative;
  top: 12px;
  left: 2px;
  z-index: -1;
}
.event:after {
  content: '';
  display: block;
  width: 295px;
  height: 75px;
  background: #fff;
  border: 1px solid #CCC;
  border-radius: 2px; 
  transform: rotate(-2deg);
  position: relative;
  top: -136px;
  z-index: -2;  
}
.event > span {
  display: block;
  width: 37px;
  background: #232323;  
  position: relative;
  top: -55px;
  left: -15px;

  /* Text */
  color: #fff;
  font-size: 10px;
  padding: 2px 7px;
  text-align: right;
}
.event > .info {
  display: inline-block;
  position: relative;
  top: -75px;
  left: 40px;

  /* Text */
  color: #232323;
  font-weight: 600;
  line-height: 25px;
}
.event > .info:first-line {
  text-transform: uppercase;
  font-size: 10px;
  margin: 10px 0 0 0;
  font-weight: 700;
}
.event > .price {
  display: inline-block;
  width: 60px;
  position: relative;
  top: -63px;
  left: 115px; 

  /* Text */
  color: #E35354;
  text-align: center;
  font-weight: 700;
}
</style>

    <script src="js/prefixfree.min.js"></script>

</head>

<body>
<table border="0" cellpadding="15" cellspacing="0" width="100%" align="center" style="float:center" >

<tr>
<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['entry_date_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Date
  </div>
  <div class="price">
    <?php echo $row['entry_date_mod'];?>
  </div>
</div>
</td>


<td>
<div class="event">
  <span>Changed</span>
  <div class="info">
  <?php
			$currentDateTime = $row['ar_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />

    AR
  </div>
  
  <div class="price">
    <?php echo $row['ar_mod'];?>
  </div>
</div>
</td>



<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['truck_no_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Truck 
  </div>
  <div class="price">
    <?php echo $row['truck_no_mod'];?>
  </div>
</div>
</td>

</tr>


<tr>
<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['srp_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    SRP
  </div>
  <div class="price">
    <?php echo $row['srp_mod'];?>
  </div>
</div>
</td>


<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['srh_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    SRH
  </div>
  <div class="price">
    <?php echo $row['srh_mod'];?>
  </div>
</div>
</td>

<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['f2r_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    F2R 
  </div>
  <div class="price">
    <?php echo $row['f2r_mod'];?>
  </div>
</div>
</td>
</tr>


<tr>
<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['bill_no_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Bill 
  </div>
  <div class="price">
    <?php echo $row['bill_no_mod'];?>
  </div>
</div>
</td>


<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['customer_name_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    CustomerName
  </div>
  <div class="price">
    <?php echo $row['customer_name_mod'];?>
  </div>
</div>
</td>



<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['customer_phone_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    CustomerPhone
  </div>
  <div class="price">
    <?php echo $row['customer_phone_mod'];?>
  </div>
</div>
</td>  
</tr>


<tr>
<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['address1_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Address_1
  </div>
  <div class="price">
    <?php echo $row['address1_mod'];?>
  </div>
</div>
</td>
  
<td>  
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['address2_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Address_2
  </div>
  <div class="price">
    <?php echo $row['address2_mod'];?>
  </div>
</div>
</td>  


<td>
  <div class="event">
  <span>Changed</span>
  <div class="info">
    <?php 	
			$currentDateTime = $row['remarks_dt'];
			$newDateTime = date('M d, Y @ h:i A', strtotime($currentDateTime));
			echo $newDateTime;?> <br />
    Remarks
  </div>
  <div class="price">
    <?php echo $row['remarks_mod'];?>
  </div>
</div>  
</td>
</tr>

</table>  


</body>
</html>

<?php
}

else
//	header("Location:loginPage.php");

?>