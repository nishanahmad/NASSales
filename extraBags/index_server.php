<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

	$requestData= $_REQUEST;	
		
	$columns = array( 
		0 =>'id', 
		1 =>'date', 
		2 =>'ar_id', 
		3=> 'qty',
		4=> 'remarks'
	);

// getting total number records without any search

	$sql = "SELECT id,date,ar_id, qty,remarks";
	$sql.=" FROM extra_bags";
	$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 26');	
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT id,date,ar_id, qty,remarks";
	$sql.=" FROM extra_bags WHERE 1 = 1";



// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) )
{ 
	$sql.=" AND id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}

if( !empty($requestData['columns'][1]['search']['value']) )
{
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
		$sql.=" AND date LIKE '%".$day."' ";	
	}

	if(preg_match($pattern_day_month1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month2, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month3, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month4, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month5, $requestData['columns'][1]['search']['value']))
	{
		$month_day_array[0] = $requestData['columns'][1]['search']['value'][3];
		$month_day_array[1] = $requestData['columns'][1]['search']['value'][4];
		$month_day_array[2] = $requestData['columns'][1]['search']['value'][2];
		$month_day_array[3] = $requestData['columns'][1]['search']['value'][0];
		$month_day_array[4] = $requestData['columns'][1]['search']['value'][1];
		
		$month_day = implode ('', $month_day_array);
		$sql.=" AND date LIKE '%".$month_day."' ";	

	}
	
	if( preg_match($pattern_month, $requestData['columns'][1]['search']['value']) )
	{
		$date = date_parse($requestData['columns'][1]['search']['value']);
		$month = $date['month'];
		$sql.=" AND month(date) = '".$month."' AND year(date)= year(CURDATE())";	
	}	

	if(	preg_match($full_pattern, $requestData['columns'][1]['search']['value'])	)
	{
		$full_date = date('Y-m-d', strtotime($requestData['columns'][1]['search']['value']));
		$sql.=" AND date LIKE '".$full_date."' ";	
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
{
	$sql.=" AND qty LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
}


$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 112');	
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 116 --'.$sql);			

/*
$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($requestData['order']));
fclose($fp);
*/


$arObjects =  mysqli_query($con,"SELECT id,ar_name FROM ar_details ORDER BY ar_name ASC ") or die(mysqli_error($con));		 
foreach($arObjects as $ar)
{
	$arMap[$ar['id']] = $ar['ar_name'];
}			

$data = array();
$total = 0;
while( $row=mysqli_fetch_array($query) ) 
{
	$nestedData=array(); 

	$nestedData[] = $row["id"];
	$nestedData[] = date('d-m-Y',strtotime($row['date']));
	$nestedData[] = $arMap[$row['ar_id']];
	$nestedData[] = $row["qty"];
		$total = $total + $row["qty"];
	$nestedData[] = $row["remarks"];

	$data[] = $nestedData;
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data,   // total data array
			"total"			  => $total,
			"sql"			  => $sql
			);

echo json_encode($json_data);  // send data as json format

}
?>