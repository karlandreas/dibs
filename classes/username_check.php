<?php
	require_once("../includes/connection.php");
	
	if (isset($_GET['username'])) {
		
		$stmt = $conn->prepare('SELECT COUNT(*) FROM del_users WHERE username = :username LIMIT 1');
		$stmt->execute(array(':username' => $_GET['username']));
		
		foreach($stmt as $row) {
			if ($row[0] > 0) {
				echo("true");
			} else {
				echo("false");
			}
		}
	}
	
?>