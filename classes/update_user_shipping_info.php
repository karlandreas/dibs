<?php
	require_once('../includes/connection.php');
	
	$valid = true;
	$sql_fields = array();
	
	if (isset($_POST['shippingFirstName'])) {
		$shippingFirstName = $_POST['shippingFirstName'];
		$sql_fields['shippingFirstName'] = $shippingFirstName;
/* 		echo("shipping First Name: " . $shippingFirstName . "<br />"); */
	} 
	if (isset($_POST['shippingLastName'])) {
		$shippingLastName = $_POST['shippingLastName'];
		$sql_fields['shippingLastName'] = $shippingLastName;
/* 		echo("shipping Last Name: " . $shippingLastName . "<br />"); */
	}
	if (isset($_POST['shippingAddress'])) {
		$shippingAddress = $_POST['shippingAddress'];
		$sql_fields['shippingAddress'] = $shippingAddress;
/* 		echo("shipping Address: " . $shippingAddress . "<br />"); */
	} 
	if (isset($_POST['shippingAddress2'])) {
		$shippingAddress2 = $_POST['shippingAddress2'];
		if ($shippingAddress2 == 'null') {
			$sql_fields['shippingAddress2'] = NULL;
			echo("shipping address set to null\n");
		} else {
			$sql_fields['shippingAddress2'] = $shippingAddress2;
		}
		
/* 		echo("shipping Address2: " . $shippingAddress2 . "<br />"); */
	} else {
		$shippingAddress2 = NULL;
	}
	if (isset($_POST['shippingPostalCode'])) {
		$shippingPostalCode = $_POST['shippingPostalCode'];
		$sql_fields['shippingPostalCode'] = $shippingPostalCode;
/* 		echo("shipping Postal Code: " . $shippingPostalCode . "<br />"); */
	} 
	if (isset($_POST['shippingPostalPlace'])) {
		$shippingPostalPlace = $_POST['shippingPostalPlace'];
		$sql_fields['shippingPostalPlace'] = $shippingPostalPlace;
/* 		echo("shipping Postal Place: " . $shippingPostalPlace . "<br />"); */
	} 
	// check
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
/* 		echo("ID: " . $id . "<br />"); */
	} else {
		$valid = false;
		echo("ID is missing");
	}
	if (count($sql_fields) < 1) {
		$valid = false;
	}
	
	if ($valid) {
		
		// Check if we already have the users shipping address
		$sql = "SELECT * FROM del_user_shipping_address WHERE extUserID=:id";
		$stmt = $conn->prepare($sql);
		$result = $stmt->execute(array(':id' => $id));
		if ($result) {
			$result = $stmt->fetchAll();
			if (count($result) > 0) { // If we have the user we update
				
				updateUserShippingInfo($conn, $id, $sql_fields);
				
			} else {  // If we don't have the user we insert
				
				insertUserShippingInfo($conn, $id, $shippingFirstName, $shippingLastName, $shippingAddress, $shippingAddress2, $shippingPostalCode, $shippingPostalPlace);
				
			}
		}
		
	}
	
	function insertUserShippingInfo($conn, $id, $shippingFirstName, $shippingLastName, $shippingAddress, $shippingAddress2, $shippingPostalCode, $shippingPostalPlace)
	{
		$sql = "INSERT INTO del_user_shipping_address 
			   (extUserID, shippingFirstName, shippingLastName, shippingAddress, shippingAddress2, shippingPostalCode, shippingPostalPlace)
			   VALUES
			   (:extUserID, :shippingFirstName, :shippingLastName, :shippingAddress, :shippingAddress2, :shippingPostalCode, :shippingPostalPlace)";
		$stmt = $conn->prepare($sql);
		if (isset($stmt)) {
			$result = $stmt->execute(array(':extUserID' => $id,
										   ':shippingFirstName' => $shippingFirstName,
										   ':shippingLastName' => $shippingLastName,
										   ':shippingAddress' => $shippingAddress,
										   ':shippingAddress2' => $shippingAddress2,
										   ':shippingPostalCode' => $shippingPostalCode,
										   ':shippingPostalPlace' => $shippingPostalPlace));
			if ($result) {
				echo("success!");
			} else {
				echo("User registration: failed");
				echo("<pre>");
					print_r($stmt->errorInfo());
				echo("</pre>");
			}
		}
	}
	
	function updateUserShippingInfo($conn, $id, $sql_fields) 
	{
		$sql = "UPDATE del_user_shipping_address SET ";
		$execute_arr = array();
		foreach($sql_fields as $key => $value) {
			$sql .= $key . "=:" . $key . ",";
			$execute_arr[':'.$key] = $value;
		}
		
		$sql = rtrim($sql, ",");
		$sql .= " WHERE extUserID=:id";
		
		$execute_arr[':id'] = $id;
/* 		echo("SQL: " . $sql . "<br />"); */
		
		$stmt = $conn->prepare($sql);
		
		if (isset($stmt)) {
			$result = $stmt->execute($execute_arr);
			if ($result) {
				echo("success!");
			} else {
				echo("User shipping address update: failed");
				echo("<pre>");
					print_r($stmt->errorInfo());
				echo("</pre>");
			}
		} else {
			echo("Statement Prepare failed<br />");
		}
	}
	
	
/*
	echo("<pre>");
		print_r($sql_fields);
	echo("</pre>");
*/
?>