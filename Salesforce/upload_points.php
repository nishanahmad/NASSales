<html>
<body>
<div class="background" align = "center">
<img src="../images/salesforce-logo.jpg">
<form method="post" action="upload_points.php" enctype="multipart/form-data" >
<br><br>
<table>
<tr><td>File</td><td><input type="file" name="ip_file" /></td></tr>
<tr><td colspan="2"><input type="submit" name="Submit" value="Upload Points" /></td></tr>
</table>
</form>
</body>
</html>
<?php
require_once ('../connect.php');
$sql="SELECT * FROM salesforce_token";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
{
	$username = $row['username'];
	$password = $row['password'];
	$security_token = $row['security_token'];
}

define("USERNAME", $username);
define("PASSWORD", $password);
define("SECURITY_TOKEN", $security_token);

require_once ('soapclient/SforceEnterpriseClient.php');
require '../connect.php';
require '../library/array_push_assoc.php';

$mySforceConnection = new SforceEnterpriseClient();
$mySforceConnection->createConnection("soapclient/enterprise.wsdl.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

$mainArray = array();
if ((isset($_FILES["ip_file"])) && ($_FILES["ip_file"]["error"] <= 0))
{
	$file_handle = fopen($_FILES["ip_file"]["tmp_name"], "r");

	$data = "";
	fgetcsv($file_handle);
	while (!feof($file_handle) ) 
	{
		$line_of_text = fgetcsv($file_handle);
		$ar_name = trim($line_of_text[0]);
		$phone = trim($line_of_text[1]);
		$points = trim($line_of_text[2]);
		$balance_points = trim($line_of_text[3]);

		$sObject = new stdclass();
		$sObject->AR_Name__c = $ar_name;
		$sObject->Phone__c = $phone;
		$sObject->Points__c = $points;
		$sObject->Balance_Points__c = $balance_points;
			
		$mainArray[] =  $sObject;
	}	
}
//var_dump($mainArray);
if(!empty($mainArray))
{
	try
	{
		$createResponse = $mySforceConnection->create($mainArray, 'Point_SMS__c');	
	}
	catch (Exception $e) 
	{
		echo $mySforceConnection->getLastRequest();
		echo $e->faultstring;
	}	
	header("Location:../index.php");		
}	
?>