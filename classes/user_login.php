<?php
	require_once("../includes/connection.php");
	include_once("user_class.php");
	session_start();
	
	$valid = true;
	$invalid_arr = array('success' => 0);
	
	if (isset($_POST['username'])) {
		$username = $_POST['username'];
/* 		echo("Username: " . $username . "<br />"); */
	} else {
		$valid = false;
		$invalid_arr['reason'] = "Username or Password is missing";
/* 		echo("Username is missing\n"); */
	}
	
	if (isset($_POST['password'])) {
		$password = $_POST['password'];
/* 		echo("Password: " . $password . "<br />"); */
	} else {
		$valid = false;
		$invalid_arr['reason'] = "Username or Password is missing";
/* 		echo("Password is missing\n"); */
	}
	
	if ($valid) {
		$stmt = $conn->prepare("SELECT * FROM del_users WHERE username=:username LIMIT 1");
		$result = $stmt->execute(array(':username' => $username));
		if ($result) {
			
			$result = $stmt->fetchAll(PDO::FETCH_CLASS, "User");
			
			if ($result == array()) {
				$invalid_arr['reason'] = "Username not found";
				echo(json_encode($invalid_arr));
			} else {
				$user = $result[0];
			
				if ($user->authenticate_user($password)) {
					$_SESSION['d_user'] = $user;
					$_SESSION['userID'] = $user->get_userExtID();
					$_SESSION['first_name'] = $user->first_name;
					$return_object = array('success' => 1, 'userExtID' => $user->get_userExtID());
					echo(json_encode($return_object));
				} else {
					$return_object = array('success' => 0, 'reason' => 'Username password combination is wrong');
					echo(json_encode($return_object));
				}
			}
			
		}
	} else {
		echo(json_encode($invalid_arr));
	}
	
/*
	echo("<pre>");
		print_r($result);
	echo("</pre>");
*/
	
?>