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

$mySforceConnection = new SforceEnterpriseClient();
$mySforceConnection->createConnection("soapclient/enterprise.wsdl.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

try 
{
	$query = 'SELECT Id FROM Point_SMS__c';
	$response = $mySforceConnection->query($query);
	$ids = array();
	foreach ($response as $result) 
	{
		array_push($ids, $result->Id);
	}

	//var_dump($recordsArray);
	$deleteResult = $mySforceConnection->delete($ids);
}
catch (Exception $e) 
{
	echo $mySforceConnection->getLastRequest();
	print_r($e);
}
header("Location:upload_points.php");
?>
