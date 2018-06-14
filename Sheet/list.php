<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>
Sheets
</title>
</head>																														<?php
	require '../connect.php';																															
	$sheets = mysqli_query($con,"SELECT * FROM sheets WHERE status IS NULL ORDER BY date ASC" ) or die(mysqli_error($con));		 	 
	foreach($sheets as $sheet)
	{																													?>
		<div class="row">
		  <div class="column" style="background-color:#ddd;">
			<p><?php echo $sheet['area'];?></p>		  
			<p><?php echo $sheet['name'] . ', ' .$sheet['phone'];?>
				<a href="close.php?id=<?php echo $sheet['id'];?>" style="float:right;margin-right:100px;"><img src="../images/delete.png" height="40px" width="40px"/></a></p>
			<p><?php echo 'Qty:'.$sheet['qty'];?></p>
			<p><?php echo date("d-m-Y",strtotime($sheet['date']));?></p>
		  </div>
		</div>																											<?php	
	}																													?>
</html>																														