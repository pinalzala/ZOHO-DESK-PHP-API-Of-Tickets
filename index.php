<?php require_once "include/functions.inc.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Zoho</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
</head>
<body>
<?php 
$token = zoho_tokenstore::acquire_token();
// echo($token2);
if (!$token) {  ?>
<a href="<?php echo zoho_auth::build_oauth_url(); ?>">Authorize me</a>
<?php }else{
echo "<h3>Zoho Services</h3>";
echo "<a href='desk/index.php' title='Click Me for ZOHO CRM'>Desk</a>";	
 } ?>
</body>
</html>
