<?php
session_start();
require_once("../includes/connection.php");

// OAuth 2 Control Flow
if (isset($_GET['error'])) {
    // LinkedIn returned an error
    header("Location: http://mserve.kajohansen.com/dibs");
    exit;
} elseif (isset($_GET['code'])) {
    if ($_GET['state'] == 'linkedin') { // User authorized your application
        
        $params = array('grant_type' => 'authorization_code',
                           'client_id' => L_KEY,
                           'client_secret' => L_SECRET,
                           'code' => $_GET['code'],
                           'redirect_uri' => L_REDIRECT_URI,
                     );
            
           // Access Token request
           $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
            
           // Tell streams to make a POST request
           $context = stream_context_create( array( 'http' => array('method' => 'POST',) ) );
        
           // Retrieve access token information
           $response = file_get_contents($url, false, $context);
        
           // Native PHP object, please
           $token = json_decode($response);
        
           // Store access token and expiration time
           $_SESSION['access_token'] = $token->access_token;  // guard this! 
           $_SESSION['expires_in']   = $token->expires_in;  // relative time (in seconds)
           $_SESSION['expires_at']   = time() + $_SESSION['expires_in'];  // absolute time
    } else {
        header("Location: http://mserve.kajohansen.com/dibs");
    }
} else { 
    header("Location: http://mserve.kajohansen.com/dibs");
}
    
 
function fetch($method, $resource, $body = '') {
    $params = array('oauth2_access_token' => $_SESSION['access_token'], 'format' => 'json', );
    // Need to use HTTPS
    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
    // Tell streams to make a (GET, POST, PUT, or DELETE) request
    $context = stream_context_create( array( 'http' => array( 'method' => $method, ) ) );
 
    // Hocus Pocus
    $response = file_get_contents($url, false, $context);
 
    // Native PHP object, please
    return json_decode($response);
}

// By default first-name, last-name, headline
$user = fetch('GET', '/v1/people/~:(id,first-name,last-name,formatted-name,headline,email-address,picture-url,public-profile-url,location:(name))');
$location = (array)$user->location;

if ($user) {
	$user_graph = array("uid" => filter_var($user->id, FILTER_SANITIZE_STRING),
						"email" => filter_var($user->emailAddress, FILTER_SANITIZE_EMAIL),
						"name" => filter_var($user->formattedName, FILTER_SANITIZE_STRING),
						"first_name" => filter_var($user->firstName, FILTER_SANITIZE_STRING),
						"last_name" => filter_var($user->lastName, FILTER_SANITIZE_STRING),
						"link" => filter_var($user->publicProfileUrl, FILTER_VALIDATE_URL),
						"picture" => filter_var($user->pictureUrl, FILTER_VALIDATE_URL),
						"locale" => filter_var($location['name'],FILTER_SANITIZE_STRING)
						);
	

	$sql = "SELECT COUNT(*) FROM del_users WHERE userExtID='" . $user_graph['uid'] . "' LIMIT 1";
	$result = $conn->query($sql);
/* 	echo("Result: " . $result->fetchColumn() . "<br>"); */
	$count = $result->fetchColumn();
	
	if ($count < 1) 
	{
		$stamp = $_SERVER['REQUEST_TIME'];
		$regDate = strftime("%Y-%m-%d %H:%M:%S", $stamp);
		
		$stmt = $conn->prepare('INSERT INTO del_users 
						  (userExtID, account, email, first_name, last_name, name, link, locale, picture, verified, regDate) 
						  VALUES 
						  (:userExtID, 2, :email, :firstNames, :lastNames, :name, :link, :locale, :picture, 0, :regDate)'
						  );
		if (isset($stmt)) {
			$result = $stmt->execute(array(':userExtID'  => $user_graph['uid'], 
								 		   ':email'      => $user_graph['email'],
								 		   ':firstNames' => $user_graph['first_name'],
								 		   ':lastNames'  => $user_graph['last_name'],
								 		   ':name'       => $user_graph['name'],
								 		   ':link'       => $user_graph['link'],
								 		   ':locale'     => $user_graph['locale'],
								 		   ':picture'    => $user_graph['picture'],
								 		   ':regDate'    =>  $regDate)
								 		   );
			if ($result) {
				$headers = 'From: noreply@delicion.no' . "\r\n";
				mail('superuser@kajohansen.com', "New Linkedin User", "NOTICE.\r\nA new google-user has registered to our site\r\n", $headers);
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
	

	$_SESSION['l_user'] = $user_graph;	
	// The access token may have been updated lazily.
	
	header('Location: ' . filter_var("http://mserve.kajohansen.com/dibs", FILTER_SANITIZE_URL));
}
?>