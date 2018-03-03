<?php
require '../connect.php';


  $query = mysqli_query($con,"SELECT ar,srp,srh,f2r,entry_date FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
  
  $total = $row["srp"] + $row["srh"] + $row["f2r"];
  
  $sql= "DELETE FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'";

  $result = mysqli_query($con, $sql) or die(mysqli_error($con));	
		 

if($_GET['clicked_from'] == 'all_sales')	
	$url = 'list.php';
else	
	$url = 'todayList.php?ar=all';

header( "Location: $url" );
?>