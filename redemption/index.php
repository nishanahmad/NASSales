<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>	
<!DOCTYPE html>
<html>
	<title>Redemption List</title>
	<head>
	<style>
	.dataTables_wrapper .dt-buttons {
	  float:none;  
	  text-align:center;
	}
	</style>	
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../css/fixedHeader.css">
		<link rel="stylesheet" type="text/css" href="../css/buttons.css">

		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="../js/fixedHeader.js"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#sales-table').DataTable( {
					"processing": true,
					"serverSide": true,
					"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 2000,		
					"ajax":{
						url :"index_server.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".sales-table-error").html("");
							$("#sales-table").append('<tbody class="sales-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#sales-table_processing").css("display","none");
										}
						   }
				} );
				
			   dataTable.on( 'xhr', function () {
				var json = dataTable.ajax.json();
				$('.sql').html(json.sql);
			} );				
			} );
		</script>

	</head>
	<body>
		<div align="center">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a
		
		</div>
<div align="center" class="gradient">
<font size=5>
<br>
<!--SQL:<span class='sql'></span><br><br-->
</b></font>

		<br><br>
			<table id="sales-table" class="display cell-border no-wrap" width="60%">
					<thead>
						<tr>
							<th style="width:90px !important">Date</th>
							<th style="width:200px !important">AR</th>
							<th style="width:50px !important">Points</th>	
							<th>Remarks</th>			
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>																																														<?php
}
else
	header("Location:../index.php");
