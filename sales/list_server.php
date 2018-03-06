<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$requestData= $_REQUEST;	
	
	$columns = array( 
		0 =>'sales_id', 
		1 =>'entry_date', 
		2 =>'ar_id', 
		3 => 'truck_no',
		4=> 'srp',
		5=> 'srh',
		6=> 'f2r',
		7=> 'bill_no',
		8=> 'customer_name',
		9=> 'remarks'
	);

// getting total number records without any search

	$sql = "SELECT sales_id,entry_date, ar_id,truck_no,srp,srh,f2r,bill_no,customer_name,remarks";
	$sql.=" FROM nas_sale";
	$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 26');	
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT sales_id,entry_date, ar_id,truck_no,srp,srh,f2r,bill_no,customer_name,remarks";
	$sql.=" FROM nas_sale where 1=1  ";



// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) )
{ 
	$sql.=" AND sales_id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}

if( !empty($requestData['columns'][1]['search']['value']) )
{  //entry_date  

	$pattern_day1 = '/^[0-9]{2}$/';
	$pattern_day2 = '/^[0-9]{2}-$/';
	$pattern_day3 = '/^[0-9]{2}-[0-9]{1}$/';
	
	$pattern_day_month1 = '/^[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month2 = '/^[0-9]{2}-[0-9]{2}-$/';
	$pattern_day_month3 = '/^[0-9]{2}-[0-9]{2}-[0-9]{1}$/';
	$pattern_day_month4 = '/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month5 = '/^[0-9]{2}-[0-9]{2}-[0-9]{3}$/';
	
	$pattern_month = '/^[a-z A-Z]/';

	$full_pattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/';
	if(preg_match($pattern_day1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day2, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day3, $requestData['columns'][1]['search']['value']))
	{
		$day_array[0] = $requestData['columns'][1]['search']['value'][0];
		$day_array[1] = $requestData['columns'][1]['search']['value'][1];
		$day = implode ('', $day_array);
		$sql.=" AND entry_date LIKE '%".$day."' ";	
	}

	if(preg_match($pattern_day_month1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month2, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month3, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month4, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month5, $requestData['columns'][1]['search']['value']))
	{
		$month_day_array[0] = $requestData['columns'][1]['search']['value'][3];
		$month_day_array[1] = $requestData['columns'][1]['search']['value'][4];
		$month_day_array[2] = $requestData['columns'][1]['search']['value'][2];
		$month_day_array[3] = $requestData['columns'][1]['search']['value'][0];
		$month_day_array[4] = $requestData['columns'][1]['search']['value'][1];
		
		$month_day = implode ('', $month_day_array);
		$sql.=" AND entry_date LIKE '%".$month_day."' ";	

	}
	
	if( preg_match($pattern_month, $requestData['columns'][1]['search']['value']) )
	{
		$date = date_parse($requestData['columns'][1]['search']['value']);
		$month = $date['month'];
		$sql.=" AND month(entry_date) = '".$month."' AND year(entry_date)= year(CURDATE())";	
	}	

	if(	preg_match($full_pattern, $requestData['columns'][1]['search']['value'])	)
	{
		$full_date = date('Y-m-d', strtotime($requestData['columns'][1]['search']['value']));
		$sql.=" AND entry_date LIKE '".$full_date."' ";	
	}	
	
}

if( !empty($requestData['columns'][2]['search']['value']) )
{  //ar
	$searchString = $requestData['columns'][2]['search']['value'];
	$arList =  mysqli_query($con, "SELECT id FROM ar_details WHERE ar_name LIKE '%".$searchString."%' ") or die(mysqli_error($con).' LINE 97');	
	$firstEntry  = true;
	foreach($arList as $ar)
	{
		if($firstEntry)
			$sql.=" AND (ar_id = '".$ar['id']."' ";		
		else
			$sql.=" OR ar_id = '".$ar['id']."' ";		
		
		$firstEntry = false;
	}
			$sql.=")";		
}

if( !empty($requestData['columns'][3]['search']['value']) )
{ //truck
	$sql.=" AND truck_no LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
}

if( !empty($requestData['columns'][4]['search']['value']) )
{ //srp
	$sql.=" AND srp LIKE '".$requestData['columns'][4]['search']['value']."%' ";
}

if( !empty($requestData['columns'][5]['search']['value']) )
{ //srh
	$sql.=" AND srh LIKE '".$requestData['columns'][5]['search']['value']."%' ";
}

if( !empty($requestData['columns'][6]['search']['value']) )
{ //f2r
	$sql.=" AND f2r LIKE '".$requestData['columns'][6]['search']['value']."%' ";
}

if( !empty($requestData['columns'][7]['search']['value']) )
{ //bill_no
	$sql.=" AND bill_no LIKE '".$requestData['columns'][7]['search']['value']."%' ";
}

if( !empty($requestData['columns'][8]['search']['value']) )
{ //customer_name
	$sql.=" AND customer_name LIKE '".$requestData['columns'][8]['search']['value']."%' ";
}

if( !empty($requestData['columns'][9]['search']['value']) )
{ //remarks
	$sql.=" AND remarks LIKE '".$requestData['columns'][9]['search']['value']."%' ";
}

$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 139');	
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 144 --'.$sql);	

$arObjects =  mysqli_query($con,"SELECT id,ar_name FROM ar_details ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
foreach($arObjects as $ar)
{
	$arMap[$ar['id']] = $ar['ar_name'];
}			

$data = array();
$srp = 0;
$srh = 0;
$f2r = 0;
while( $row=mysqli_fetch_array($query) ) 
{
	$nestedData=array(); 

	$nestedData[] = '<a href="edit.php?clicked_from=all_sales&sales_id='.$row["sales_id"].'">'.$row["sales_id"].'</a>';
	$nestedData[] = date('d-m-Y',strtotime($row['entry_date']));
	$nestedData[] = $arMap[$row['ar_id']];
	$nestedData[] = $row["truck_no"];
	$nestedData[] = $row["srp"];
		$srp = $srp +  $row["srp"];
	$nestedData[] = $row["srh"];
		$srh = $srh + $row["srh"];
	$nestedData[] = $row["f2r"];
		$f2r = $f2r + $row["f2r"];
	$nestedData[] = $row["bill_no"];
	$nestedData[] = $row["customer_name"];
	$nestedData[] = $row["remarks"];

	$data[] = $nestedData;
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data,   // total data array
			"srp"			  => $srp,
			"srh"			  => $srh,
			"f2r"			  => $f2r,
			"sql"			  => $sql
			);

echo json_encode($json_data);  // send data as json format

}
?>