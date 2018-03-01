<?php
// SOAP_CLIENT_BASEDIR - folder that contains the PHP Toolkit and your WSDL
// $USERNAME - variable that contains your Salesforce.com username (must be in the form of an email)
// $PASSWORD - variable that contains your Salesforce.ocm password


define("SOAP_CLIENT_BASEDIR", "../soapclient");
$USERNAME='nasagencies@gmail.com.sbnas';
$PASSWORD='masontrackupn12LlJuTLUDKugr87qZrRPFc20I';
//$TOKEN = 'LlJuTLUDKugr87qZrRPFc20I';

require_once (SOAP_CLIENT_BASEDIR.'/SforcePartnerClient.php');
require_once (SOAP_CLIENT_BASEDIR.'/SforceHeaderOptions.php');


	$mySforceConnection = new SforcePartnerClient();
	$mySoapClient = $mySforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
	$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);

	$query = "SELECT Id, Name from Account";
	$response = $mySforceConnection->query($query);
	echo "Results of query '$query'<br/><br/>\n";
		foreach ($response->records as $record) 
		{
			echo $record->Id . ": " . $record->Name . "<br/>\n";
		}
	 
?>
