<?php 
session_start();
require_once("../includes/connection.php");
require_once('../libraries/facebook/facebook.php');

$facebook = new Facebook(array( 'appId' => FB_KEY,  'secret' => FB_SECRET ));

if (!isset($_SESSION['f_user'])) 
{
	if ($f_user = $facebook->getUser()) {
		$user_graph = $facebook->api('/me'); // we get the $user_praph
		$stamp = $_SERVER['REQUEST_TIME'];
		$regDate = strftime("%Y-%m-%d %H:%M:%S", $stamp); // for del_users regDate
		
		if ($user_graph['verified']) {
			// we only put the user information we need into the session
			$_SESSION['f_user'] = $user_graph; 
			$_SESSION['fields'] = $facebook->api('/me?fields=cover,picture.type(large)');
			// SAVE FACEBOOK USER IF NOT IN DB
			$sql = "SELECT COUNT(*) FROM del_users WHERE userExtID='" . $user_graph['id'] . "' LIMIT 1";
			$result = $conn->query($sql);
			$count = $result->fetchColumn();
			
			if ($count < 1) 
			{
			    if (isset($user_graph['middle_name'])) {
				    $first_names = $user_graph['first_name'] . " " . $user_graph['middle_name'];
			    } else {
				    $first_names = $user_graph['first_name'];
			    }
				$stmt = $conn->prepare('INSERT INTO del_users 
							  (userExtID, account, email, first_name, last_name, name, username, gender, link, locale, picture, cover, verified, regDate) 
							  VALUES 
							  (:userExtID, 1, :email, :firstNames, :lastNames, :name, :username, :gender, :link, :locale, :picture, :cover, :verified, :regDate)');
							  
				if (isset($stmt)) {
					$result = $stmt->execute(array(':userExtID'  => $user_graph['id'], 
										 		   ':email'      => $user_graph['email'],
										 		   ':firstNames' => $first_names,
										 		   ':lastNames'  => $user_graph['last_name'],
										 		   ':name'       => $user_graph['name'],
										 		   ':username'   => $user_graph['username'],
										 		   ':gender'     => $user_graph['gender'],
										 		   ':link'       => $user_graph['link'],
										 		   ':locale'     => $user_graph['locale'],
										 		   ':picture'    => $_SESSION['fields']['picture']['data']['url'],
										 		   ':cover'      => $_SESSION['fields']['cover']['source'],
										 		   ':verified'   => $user_graph['verified'],
										 		   ':regDate'    =>  $regDate)
										 		   );
					if ($result) {
						$headers = 'From: noreply@delicion.no' . "\r\n";
						mail('superuser@kajohansen.com', "New Facebook User", "NOTICE.\r\nA new google-user has registered to our site\r\n", $headers);
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
	} // End f_user
} // End if SESSION

header('Location: ' . filter_var("http://mserve.kajohansen.com/dibs", FILTER_SANITIZE_URL));

 ?>