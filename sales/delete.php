<?php
require '../connect.php';

  $sql= "DELETE FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'";

  $result = mysqli_query($con, $sql) or die(mysqli_error($con));	
		 

if($_GET['clicked_from'] == 'all_sales')	
	$url = 'list.php';
else	
	$url = 'todayList.php?ar=all';

header( "Location: $url" );
?>