<?php
	require_once('../includes/connection.php');
	
	$valid = true;
	$sql_fields = array();
	
	if (isset($_POST['billingFirstName'])) {
		$billingFirstName = $_POST['billingFirstName'];
		$sql_fields['billingFirstName'] = $billingFirstName;
/* 		echo("Billing First Name: " . $billingFirstName . "<br />"); */
	} 
	if (isset($_POST['billingLastName'])) {
		$billingLastName = $_POST['billingLastName'];
		$sql_fields['billingLastName'] = $billingLastName;
/* 		echo("Billing Last Name: " . $billingLastName . "<br />"); */
	}
	if (isset($_POST['billingAddress'])) {
		$billingAddress = $_POST['billingAddress'];
		$sql_fields['billingAddress'] = $billingAddress;
/* 		echo("Billing Address: " . $billingAddress . "<br />"); */
	} 
	if (isset($_POST['billingAddress2'])) {
		$billingAddress2 = $_POST['billingAddress2'];
		if ($billingAddress2 == 'null') {
			$sql_fields['billingAddress2'] = NULL;
		} else {
			$sql_fields['billingAddress2'] = $billingAddress2;
		}
		
/* 		echo("Billing Address2: " . $billingAddress2 . "<br />"); */
	} else {
		$billingAddress2 = NULL;
	}
	if (isset($_POST['billingPostalCode'])) {
		$billingPostalCode = $_POST['billingPostalCode'];
		$sql_fields['billingPostalCode'] = $billingPostalCode;
/* 		echo("Billing Postal Code: " . $billingPostalCode . "<br />"); */
	} 
	if (isset($_POST['billingPostalPlace'])) {
		$billingPostalPlace = $_POST['billingPostalPlace'];
		$sql_fields['billingPostalPlace'] = $billingPostalPlace;
/* 		echo("Billing Postal Place: " . $billingPostalPlace . "<br />"); */
	} 
	if (isset($_POST['billingMobile'])) {
		$billingMobile = $_POST['billingMobile'];
		$sql_fields['billingMobile'] = $billingMobile;
/* 		echo("Billing Mobile: " . $billingMobile . "<br />"); */
	} else {
		$billingMobile = NULL;
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
	
		// Check if we already have the users billing address
		$sql = "SELECT * FROM del_user_billing_address WHERE extUserID=:id";
		$stmt = $conn->prepare($sql);
		$result = $stmt->execute(array(':id' => $id));
		if ($result) {
			$result = $stmt->fetchAll();
			if (count($result) > 0) { // If we have the user we update
				
				updateUserBillingInfo($conn, $id, $sql_fields);
				
			} else {  // If we don't have the user we insert
				
				insertUserBillingInfo($conn, $id, $billingFirstName, $billingLastName, $billingAddress, $billingAddress2, $billingPostalCode, $billingPostalPlace, $billingMobile);
				
			}
		}
		
	}
	
	function insertUserBillingInfo($conn, $id, $billingFirstName, $billingLastName, $billingAddress, $billingAddress2, $billingPostalCode, $billingPostalPlace, $billingMobile) 
	{
		$sql = "INSERT INTO del_user_billing_address 
			   (extUserID, billingFirstName, billingLastName, billingAddress, billingAddress2, billingPostalCode, billingPostalPlace, billingMobile)
			   VALUES
			   (:extUserID, :billingFirstName, :billingLastName, :billingAddress, :billingAddress2, :billingPostalCode, :billingPostalPlace, :billingMobile)";
		$stmt = $conn->prepare($sql);
		if (isset($stmt)) {
			$result = $stmt->execute(array(':extUserID' => $id,
										   ':billingFirstName' => $billingFirstName,
										   ':billingLastName' => $billingLastName,
										   ':billingAddress' => $billingAddress,
										   ':billingAddress2' => $billingAddress2,
										   ':billingPostalCode' => $billingPostalCode,
										   ':billingPostalPlace' => $billingPostalPlace,
										   ':billingMobile' => $billingMobile));
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
	
	function updateUserBillingInfo($conn, $id, $sql_fields) 
	{
		$sql = "UPDATE del_user_billing_address SET ";
		$execute_arr = array();
		
		foreach($sql_fields as $key => $value) {
			$sql .= $key . "=:" . $key . ",";
			$execute_arr[':'.$key] = $value;
		}
		
		$sql = rtrim($sql, ",");
		$sql .= " WHERE extUserID=:id";
		$execute_arr[':id'] = $id;
		$stmt = $conn->prepare($sql);
		
		if (isset($stmt)) {
			$result = $stmt->execute($execute_arr);
			if ($result) {
				echo("success!");
			} else {
				echo("User registration: failed");
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