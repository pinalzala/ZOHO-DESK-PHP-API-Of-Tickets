<?php
// callback.php

require_once "include/functions.inc.php";

$response = zoho_auth::get_oauth_token($_GET['code']); 

if (zoho_tokenstore::save_tokens_to_store($response)) {
	header("Location: index.php");
} else {
	echo "error";
}

?>