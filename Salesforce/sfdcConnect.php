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

$wsdl  = 'WSDL.xml';
$user  = $username;
$pass  = $password;
$token = $security_token;

$client = new SforceEnterpriseClient();
$client->createConnection($wsdl);
$client->login($user, $pass . $token);
?>