
<?php require_once "crm_functions.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Zoho CRM</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
</head>
<body>
<?php 
$token = zoho_crm::acquire_token();
if (!$token) {  ?>

	<a href="<?php echo zoho_crm::build_oauth_url(); ?>">Authorize me</a>

	<?php }else{

//create new tickets

$url ="https://desk.zoho.in/api/v1/tickets";
	
$data = '{
			"contactId" : "your contactId",
			"subject" : "Real Time analysis Requirement'.time().'",
			"departmentId" : "your departmentId"
		}';

	try {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"orgId:your orgId",
		'Authorization:Zoho-oauthtoken '.$token,
	));

	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$output = curl_exec($ch);
	 $error = curl_error($ch);

	 $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	} catch (Exception $e) {
	}
	if ($httpcode == "200") {
		$response = json_decode($output, true);
	} else {
		$response = array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
	}
	echo "<pre>";
	print_r($response); 	
	echo "<pre>";


	echo "<a href='index.php' title='Back to CRM page'>Back</a>";


	 } ?>
</body>
</html>