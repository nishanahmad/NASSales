<?php
require_once ('soapclient/SforceEnterpriseClient.php');
require '../connect.php';
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



$mySforceConnection = new SforceEnterpriseClient();
$mySforceConnection->createConnection("soapclient/enterprise.wsdl.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

$mainArray = array();
$post_date = $_POST["date"];
$date = date('Y-m-d', strtotime($post_date));
$sms_date = date('d/M', strtotime($post_date));

$fp = fopen('../sfdc_date.json', 'w');
fwrite($fp, json_encode($date));
fclose($fp);

$ar_query = mysqli_query($con,"SELECT ar_name,salesforce_id FROM ar_details WHERE salesforce_id NOT LIKE '%GENERAL%' ") or die(mysqli_error($con));	
while($row = mysqli_fetch_array($ar_query,MYSQLI_ASSOC))
{
	$detailArray = array();
	$sum = 0;
	$lpp = 0;
	$hdpe = 0;
	$cstl = 0;
	$ar = $row["ar_name"];
	$salesforce_id = $row["salesforce_id"];
	$sum_query = mysqli_query($con,"SELECT srp,srh,f2r,return_bag FROM nas_sale WHERE entry_date = '$date' AND ar = '$ar' ") or die(mysqli_error($con));	
	while($sales = mysqli_fetch_array($sum_query,MYSQLI_ASSOC))
	{
		$lpp = $lpp + $sales["srp"];
		$hdpe = $hdpe + $sales["srh"];
		$cstl = $cstl + $sales["f2r"];
		$sum = $sum + $sales["srp"] + $sales["srh"] + $sales["f2r"];
	}
	
	$detailArray = array($sms_date,$lpp,$hdpe,$cstl,$sum);
	$mainArray[$salesforce_id] =   $detailArray;
	
}
//var_dump($mainArray);

	$recordsArray = array();
	foreach($mainArray as $id => $details)
	{
		try 
		{		
			$query = "SELECT Id, order_Date__c, Lpp__c, Hdpe__c, Coastal__c, Total__c FROM AR_SR__c WHERE Id='$id'";
			$response = $mySforceConnection->query($query);

			foreach ($response->records as $record) 
			{
				//echo $record->Name;
				$record -> Order_Date__c = $details[0];
				$record -> Lpp__c = $details[1];
				$record -> Hdpe__c = $details[2];
				$record -> Coastal__c = $details[3];
				$record -> Total__c = $details[4];
				$recordsArray[] = $record;
			}
		}
				 
		catch (Exception $e) 
		{
			echo "Exception ".$e->faultstring."<br/><br/>\n";
			echo "Last Request:<br/><br/>\n";
			echo $mySforceConnection->getLastRequestHeaders();
			echo "<br/><br/>\n";
			echo $mySforceConnection->getLastRequest();
			echo "<br/><br/>\n";
			echo "Last Response:<br/><br/>\n";
			echo $mySforceConnection->getLastResponseHeaders();
			echo "<br/><br/>\n";
			echo $mySforceConnection->getLastResponse();
		}
	}
	$mySforceConnection->update($recordsArray, 'AR_SR__C');
	
	header("Location:../index.php");	
?>