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
	$query = 'SELECT Id,Order_Date__c, Lpp__c, Hdpe__c, Coastal__c, Total__c FROM AR_SR__c WHERE Total__c != null';
	$response = $mySforceConnection->query($query);

	$recordsArray = array();
	foreach ($response->records as $record) 
	{
		$record->fieldsToNull = array("Order_Date__c", "Lpp__c", "Hdpe__c", "Coastal__c", "Total__c");
		$recordsArray[] = $record;
	}

	//var_dump($recordsArray);
	$updateResult = $mySforceConnection->update($recordsArray, 'AR_SR__c');
}
catch (Exception $e) 
{
	echo $mySforceConnection->getLastRequest();
	print_r($e);
}
header("Location:ar_update_salesforce.php");
?>
