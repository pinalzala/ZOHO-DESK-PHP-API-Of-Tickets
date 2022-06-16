<?php
require_once 'config/config.php';
define("token_store", "token/tokens"); 
class zoho {
	public $access_token = '';

	public function __construct($passed_access_token) {
		$this->access_token = $passed_access_token;
	}
	
	protected function curl_get($url)
	{

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
			"Accept: application/json",
			"Authorization: Bearer " . $this->access_token
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$output = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($output, true);
		return $response;
	}


	protected function curl_post($uri, $inputarray, $access_token) {
		$trimmed = json_encode($inputarray);
		try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token,
		));
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $trimmed);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		} catch (Exception $e) {
		}
		
		if ($httpcode == "201") {
			return json_decode($output, true);
		} else {
			return array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
		}
	}



	protected function curl_put($uri, $fp) {
	  $output = "";
	  try {
	  	$pointer = fopen($fp, 'r+');
	  	$stat = fstat($pointer);
	  	$pointersize = $stat['size'];
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_PUT, true);
		curl_setopt($ch, CURLOPT_INFILE, $pointer);
		curl_setopt($ch, CURLOPT_INFILESIZE, (int)$pointersize);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));			
		
		$output = curl_exec($ch);
		
		echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); die;
	  } catch (Exception $e) {
	  }
	  	if ($httpcode == "200" || $httpcode == "201") {
	  		return json_decode($output, true);
	  	} else {
	  		return array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
	  	}
		
	}

	// Internally used function to make a DELETE request to SkyDrive.
	protected function curl_delete($uri) {
	  $output = "";
	  try {
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');    
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	  } catch (Exception $e) {
	  }
	  	if ($httpcode == "200") {
	  		return json_decode($output, true);
	  	} else {
	  		return array('error' => 'HTTP status code not expected - got ', 'description' => $httpcode);
	  	}
	}
	

}

class zoho_auth {

 
	//oauth url
	public static function build_oauth_url() {
		$response = "https://accounts.zoho.com/oauth/v2/auth?scope=ZohoAnalytics.data.all,ZohoAnalytics.modeling.create,ZohoCRM.users.ALL,ZohoCRM.org.ALL,ZohoCRM.modules.ALL,ZohoCRM.modules.contacts.UPDATE,ZohoCRM.settings.ALL,ZohoCRM.modules.contacts.ALL,Desk.tickets.ALL&client_id=".client_id."&response_type=code&access_type=offline&prompt=consent&redirect_uri=".urlencode(callback_uri);
		return $response;
	}
	

	//oauth token
	public static function get_oauth_token($auth) {
		$arraytoreturn = array();

		$output = "";
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.in/oauth/v2/token");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/x-www-form-urlencoded',
				));
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);		

			$data = "client_id=".client_id."&redirect_uri=".urlencode(callback_uri)."&client_secret=".urlencode(client_secret)."&code=".$auth."&grant_type=authorization_code";

			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			$output = curl_exec($ch);
			
		} catch (Exception $e) {
		}
		$out2 = json_decode($output, true);
		$arraytoreturn = Array('access_token' => $out2['access_token'], 'refresh_token' => $out2['refresh_token'], 'expires_in' => $out2['expires_in'], 'api_domain'=>$out2['api_domain']); 
		return $arraytoreturn;
	}


	//refresh oauth token
	public static function refresh_oauth_token($refresh) {
		$arraytoreturn = array();
		$output = "";
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.in/oauth/v2/token"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/x-www-form-urlencoded',
				));
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);		

			$data = "client_id=".client_id."&redirect_uri=".urlencode(callback_uri1)."&client_secret=".urlencode(client_secret1)."&refresh_token=".$refresh."&grant_type=refresh_token&scope=ZohoAnalytics.data.all,ZohoAnalytics.modeling.create,Desk.tickets.ALL,ZohoCRM.modules.ALL"; 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$output = curl_exec($ch);
		} catch (Exception $e) {
		}
	
		$out2 = json_decode($output, true);
		$arraytoreturn = Array('access_token' => $out2['access_token'], 'expires_in' => $out2['expires_in'], 'api_domain'=>$out2['api_domain']);
		return $arraytoreturn;
	}
	
	
	
}

class zoho_tokenstore {

 

	public static function acquire_token() {
		
		$response = zoho_tokenstore::get_tokens_from_store();
		if (empty($response['access_token'])) {	// No token at all, needs to go through login flow. Return false to indicate this.
			return false;
			exit;
		}
		else {
			if (time() > (int)$response['access_token_expires']) { // Token needs refreshing. Refresh it and then return the new one.
				$refreshed = zoho_auth::refresh_oauth_token($response['refresh_token']);

				$get_refresh_token = zoho_tokenstore::get_tokens_from_store();

				$refreshed['refresh_token'] = $get_refresh_token['refresh_token'];
				if (zoho_tokenstore::save_tokens_to_store($refreshed)) {
					$newtokens = zoho_tokenstore::get_tokens_from_store();
					return $newtokens['access_token'];
				}
				exit;
			} else {
				return $response['access_token']; // Token currently valid. Return it.
				exit;
			}
	}
}



	public static function get_tokens_from_store() {
		$response = json_decode(file_get_contents(token_store), TRUE);
		return $response;
	}
	
	/* Version V1 */


	public static function save_tokens_to_store($tokens) {
		$tokentosave = array();
		$tokentosave = array('access_token' => $tokens['access_token'], 'refresh_token' => $tokens['refresh_token'], 'access_token_expires' => (time() + (int)$tokens['expires_in']), 'api_domain'=>$tokens['api_domain']);
		if (file_put_contents(token_store, json_encode($tokentosave))) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function destroy_tokens_in_store() {
		
			if (file_put_contents(token_store, "loggedout")) {
			return true;
		} else {
			return false;
		}
		
	}

}

?>
