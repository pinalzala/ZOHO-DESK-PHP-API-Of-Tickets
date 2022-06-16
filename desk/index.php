
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
echo '<a href="addticket.php"><h3>Add Tickets </h3></a>';
echo '<a href="reply.php"><h3>Tickets Replay</h3></a>';
		echo '<h3>tickets </h3>';
$curl = curl_init();
// get all tickets
curl_setopt($curl, CURLOPT_URL, "https://desk.zoho.in/api/v1/tickets");
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	"orgId:your orgId",
	"Authorization: Zoho-oauthtoken ".$token."" 
)); 

curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  $output = curl_exec($curl);
 $errs = curl_error($curl);
curl_close($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($httpcode == "200") {
	$response = json_decode($output, true);
} else {
	$response = array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
}
echo "<pre>";
print_r($response); 
echo "<pre>";
$organizationsID="Your organizationsID";
		echo '<h3>tickets comments</h3>';
		//get tickets comments
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://desk.zoho.in/api/v1/tickets");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"orgId:".$organizationsID."",
        "Authorization: Zoho-oauthtoken ".$token."" 
    )); 

    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($curl);
	$errs = curl_error($curl);
    curl_close($curl);
	$contect = json_decode($content);
	
	foreach($contect as $val1){
	foreach($val1 as $val){
	
	$ticketID = $val->id;
	$contactID = $val->contactId;
	$tckID = "";
	$phone = '+919898989898';
	// retrive ticket end 

// get ticket reply start
//$params="limit=15&sortBy=commentedTime&from=1";
	try {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://desk.zoho.in/api/v1/tickets/$ticketID/comments");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"orgId:".$organizationsID."",
        "Authorization: Zoho-oauthtoken ".$token1."" 
    )); 

    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$content = curl_exec($curl);	
	$errs = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	} catch (Exception $e) {
	}
	$response = json_decode($content);
	echo "<pre>";
	print_r($response);
	echo "<pre>";
	if($httpcode == "200"){
	$response = json_decode($content);
	
	foreach($response->data as $value){
	$commentsID = $value->id;
	$tickets_comments = array();
	
	if(empty($tickets_comments)){
	$messageID = $value->id;
	$message = $value->content;
	$checkMessage = array();
	if(empty($checkMessage)){
	// reply to bagachat start
    $url ="https://push.bagachat.com/api/sendtransactionalmsg.bg";
	//$token_r ='QKRV10516369AP'; 
	$tokentickets ='QKRV10516369AP:A';
	//$token_r ='JNJU1160712DTPZ:A';
	$tokentickets = base64_encode($tokentickets);
    $data = '{
                "conversationname" : "'.$phone.'",
				"message" : "'.$message.'"
            }';

		try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic '.$tokentickets,
		    'Content-Type: application/json',
		));

		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$output = curl_exec($ch);
		$error = curl_error($ch);
		$httpcod = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		} catch (Exception $e) {
		}
		if ($httpcode_r == "200") {
		$response = json_decode($output, true);
		
		} else {
			$response = array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
		}
		echo "<pre>";
		print_r($response);
		echo "<pre>";	
	// reply to bagachat end 
	}
	}
	}
	}else{
	$response = array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
	}
	
	
	}
	
	
	
	}
	
	die;


	
		
	 } ?>
</body>
</html>