<?php require_once "crm_functions.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Zoho CRM Ticket</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" /> 
	<meta http-equiv="refresh" content="300" >
	<meta content="" name="description" />
	<meta content="" name="author" />
</head>
<body>
<?php 
$token2 = zoho_crm::acquire_token();
if (!$token2) {  ?>
	<a href="<?php echo zoho_crm::build_oauth_url(); ?>">Authorize me</a><?php }else{
	$token1=$token2;
		
	$organizationsID="60001673303";
  
	$curl = curl_init();
echo $token1;
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://analyticsapi.zoho.com/api/EmailAddress/WorkspaceName/TableName',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => 'ZOHO_ACTION=ADDROW&ZOHO_OUTPUT_FORMAT=XML&ZOHO_ERROR_FORMAT=XML&ZOHO_API_VERSION=1.0&Id=999&Name=Gary&Date%20Of%20Birth=12-Jun-1980&Salary=10000&Country=USA',
	  CURLOPT_HTTPHEADER => array(
		'Authorization: Zoho-oauthtoken '.$token1,
		'Content-Type: application/x-www-form-urlencoded',
	  ),
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
		echo "<pre>";
		print_r($response);
		echo "<pre>";

	 


	
 } ?>
</body>
</html>