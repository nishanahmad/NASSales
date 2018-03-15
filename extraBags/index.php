<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>	
<!DOCTYPE html>
<html>
	<title>Sales List</title>
	<head>
	<style>
		.dataTables_wrapper .dt-buttons {
		  float:none;  
		  text-align:center;
		}
		.dataTables_length{
			display: none;
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
				$('.total').html(json.total);
				$('.sql').html(json.sql);
			} );				
				
				
				$("#sales-table_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
			} );
		</script>

	</head>
	<body>
		<div align="center">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
		</div>
<div align="center" class="gradient">
<font size=5>
<br>
<!--SQL:<span class='sql'></span><br><br-->
<b>TOTAL : <span class='total'></span>
</b></font>
		<br/><br/>
			<input type="text" data-column="1"  class="search-input-text textarea" placeholder="Date">&nbsp&nbsp
			<input type="text" data-column="2"  class="search-input-text textarea" placeholder="AR">&nbsp&nbsp
			<input type="text" data-column="3" style="width:50px" class="search-input-text textarea" placeholder="qty">&nbsp&nbsp

		<br/><br/>
			<a href="new.php" class="link" class="">ADD EXTRA BAGS</a>		
		<br/><br/>
			<table id="sales-table" class="display cell-border no-wrap" style="width:60%">
					<thead>
						<tr>
							<th style="width:20px;">Id</th>
							<th style="width:100px;">Date</th>
							<th style="width:250px;">AR</th>
							<th style="width:40px;">Qty</th>							
							<th>REMARKS</th>							
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>																				<?php
}
else
	header("Location:../index.php");
