<?php
session_start();
require_once '../libraries/google/Google_Client.php';
require_once '../libraries/google/contrib/Google_Oauth2Service.php';
require_once('../includes/connection.php');

$google_client = new Google_Client();

if (isset($_GET['state']) && $_GET['state'] == "google") {
	$google_client->setApplicationName("DIBS Access");
	// Visit https://code.google.com/apis/console?api=plus to generate your
	// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
	$google_client->setClientId(G_KEY);
	$google_client->setClientSecret(G_SECRET);
	$google_client->setRedirectUri('http://mserve.kajohansen.com/dibs/oauthcallback/google.php');
	// $google_client->setDeveloperKey('insert_your_developer_key');
	
	$google_client->authenticate($_GET['code']);
	$_SESSION['token'] = $google_client->getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	return;
}

if (isset($_SESSION['token'])) {
	$google_client->setAccessToken($_SESSION['token']);
}

if ($google_client->getAccessToken()) {
	$oauth2 = new Google_Oauth2Service($google_client);
	$user = $oauth2->userinfo->get();
	
	if ($user['verified_email']) {
		$verified = 1;
		$stamp = $_SERVER['REQUEST_TIME'];
		$regDate = strftime("%Y-%m-%d %H:%M:%S", $stamp);
	} else {
		$verified = 0;
	}
	
	$user_graph = array("uid" => $user['id'],
						"email" => filter_var($user['email'], FILTER_SANITIZE_EMAIL),
						"verified" => $verified,
						"name" => $user['name'],
						"given_name" => $user['given_name'],
						"family_name" => $user['family_name'],
						"link" => filter_var($user['link'], FILTER_VALIDATE_URL),
						"picture" => filter_var($user['picture'], FILTER_VALIDATE_URL),
						"gender" => $user['gender'],
						"locale" => $user['locale']);
	
						
	if ($verified) 
	{
		$sql = "SELECT COUNT(*) FROM del_users WHERE userExtID='" . $user['id'] . "' LIMIT 1";
		$result = $conn->query($sql);
		/* 	echo("Result: " . $result->fetchColumn() . "<br>"); */
		$count = $result->fetchColumn();
		
		if ($count < 1) 
		{
			$stmt = $conn->prepare('INSERT INTO del_users 
							  (userExtID, account, email, first_name, last_name, name, gender, link, locale, picture, verified, regDate) 
							  VALUES 
							  (:userExtID, 2, :email, :firstNames, :lastNames, :name, :gender, :link, :locale, :picture, :verified, :regDate)'
							  );
			if (isset($stmt)) {
				$result = $stmt->execute(array(':userExtID'  => $user_graph['uid'], 
									 		   ':email'      => $user_graph['email'],
									 		   ':firstNames' => $user_graph['given_name'],
									 		   ':lastNames'  => $user_graph['family_name'],
									 		   ':name'       => $user_graph['name'],
									 		   ':gender'     => $user_graph['gender'],
									 		   ':link'       => $user_graph['link'],
									 		   ':locale'     => $user_graph['locale'],
									 		   ':picture'    => $user_graph['picture'],
									 		   ':verified'   => $verified,
									 		   ':regDate'    =>  $regDate)
									 		   );
				if ($result) {
					$headers = 'From: noreply@delicion.no' . "\r\n";
					mail('superuser@kajohansen.com', "New Google User", "NOTICE.\r\nA new google-user has registered to our site\r\n", $headers);
					echo("success!");
				} else {
					echo("User registration: failed");
					echo("<pre>");
						print_r($stmt->errorInfo());
					echo("</pre>");
				}
			}
		} else {
			echo("Got user");
		}
	} else {
		echo("--- Got Unverified User");
	}
							
						
	$_SESSION['g_user'] = $user_graph;	
	// The access token may have been updated lazily.
	$_SESSION['token'] = $google_client->getAccessToken();
} else {
	$authUrl = $google_client->createAuthUrl();
}

header('Location: ' . filter_var("http://mserve.kajohansen.com/dibs", FILTER_SANITIZE_URL));
?>

