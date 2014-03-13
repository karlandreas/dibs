<?php
	require_once("../includes/connection.php");
	
	$valid = true;
	// check
	if (isset($_POST['username'])) {
		$username = $_POST['username'];
	} else {
		$valid = false;
		echo("Username is missing<br />");
	}
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	} else {
		$valid = false;
		echo("Email is missing<br />");
	}
	if (isset($_POST['firstNames'])) {
		$firstNames = $_POST['firstNames'];
	} else {
		$valid = false;
		echo("First Names is missing<br />");
	}
	if (isset($_POST['lastNames'])) {
		$lastNames = $_POST['lastNames'];
	} else {
		$valid = false;
		echo("Last Names is missing<br />");
	}
	// check
	if (isset($_SERVER['REQUEST_TIME']) && isset($firstNames) && isset($lastNames)) 
	{
		$name = $firstNames . " " . $lastNames;
		$stamp = $_SERVER['REQUEST_TIME'];
		$regDate = strftime("%Y-%m-%d %H:%M:%S", $stamp);
		$userExtID = set_user_id($name, $_SERVER['REQUEST_TIME']);
	} 
	else 
	{
		$valid = false;
		echo("regDate, userExtID and name could not be made<br />");
	}
	// check
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) 
	{
		$locale_arr = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$locale = $locale_arr[0];
	} 
	else 
	{
		$locale = "";
	}
	// check
	if (isset($_POST['password']) && isset($username) && isset($stamp)) 
	{
		$password_plain = $_POST['password'];
		$salt = make_salt($username, $stamp);
		$password = hash_with_salt($password_plain, $salt);
	} 
	else 
	{
		$valid = false;
		echo("Password could not be created\n");
	}
	
	$stmt = $conn->prepare("SELECT * FROM del_users WHERE email=:email");
	$result = $stmt->execute(array(':email' => $email));
	
	if ($result) {
		$result = $stmt->fetchAll();
		if (count($result) > 0) {
			$valid = false;
			echo("email address is already in use<br />");
		}
	} else {
		echo("Select email failed<br />");
	}
	// if we have only valid records, continue to register the user
	if ($valid) 
	{
		$stmt = $conn->prepare('INSERT INTO del_users 
							  (userExtID, account, email, first_name, last_name, name, locale, username, password, salt, verified, regDate) 
							  VALUES 
							  (:userExtID, 0, :email, :firstNames, :lastNames, :name, :locale, :username, :password, :salt, 0, :regDate)'
							  );
		if (isset($stmt)) {
			$result = $stmt->execute(array(':userExtID' => $userExtID, 
							 		   ':email' => $email, 
							 		   ':firstNames' => $firstNames, 
							 		   ':lastNames' => $lastNames, 
							 		   ':name' => $name, 
							 		   ':locale' => $locale, 
							 		   ':username' => $username, 
							 		   ':password' => $password,
							 		   ':salt' => $salt,
							 		   ':regDate' =>  $regDate)
							 		   );
			if ($result) {
				$headers = 'From: noreply@delicion.no' . "\r\n";
				$link = "http://mserve.kajohansen.com/dibs/classes/validate_user.php?id=" . $userExtID . "&mail=" . $email;
				mail($email, "Validate Email Address", "Hi\r\nTo validate your Delicion account hit the link below\r\n" . $link, $headers);
				echo("success!");
			} else {
				echo("User registration: failed");
				echo("<pre>");
					print_r($stmt->errorInfo());
				echo("</pre>");
			}
		} else {
			echo("Prepare Error:<br /><pre>");
				print_r($conn->errorInfo());
			echo("</pre>");
		}
		
	} else {
		echo("Not a valid form,\n<br />");
	}
	
	// User registration functions
	function set_user_id($users_name, $timestamp) {
		$uname_arr = explode(" ", $users_name);
		$initials_arr = array();
		foreach($uname_arr as $name_part) {
			array_push($initials_arr, substr($name_part, 0, 1));
		}
		$initials = implode("", $initials_arr);
		return $initials . $timestamp;
	}
	
	function hash_with_salt($pass, $salt) {
		return sha1("Ha litt" . $salt . "paa" . $pass);
	}
	
	function make_salt($username, $stamp) {
		return sha1("Bruk" . $username . "med" . $stamp . "som salt");
	}
	
?>