<?php
	require_once("../includes/connection.php");
	
	$myFile = getcwd() . "/../log/callback.txt";
	$fh = fopen($myFile, 'a+') or die("Log File Cannot Be Opened.");
	
	fwrite($fh, "-------TRANSACTION-------\n");
	fwrite($fh, "Date: " . strftime("%Y-%m-%d %H:%M:%S", time()) . "\n");
	foreach ($_POST as $key => $value) {
		fwrite($fh, $key . " = " . $value . "; ");
	}
	
	$valid = true;
	if (isset($_POST['status'])) {
		if ($_POST['status'] != "ACCEPTED") {
			fwrite($fh, "Error: NOT ACCEPTED\n");
		} else {
			$status = $_POST['status'];
		}
	}
	if (!isset($_POST['s_userID'])) {
		$valid = false;
		fwrite($fh, "Error: User ID is missing\n");
	} else {
		$userExtID = $_POST['s_userID'];
	}
	if (!isset($_POST['amount'])) {
		$valid = false;
		fwrite($fh, "Error: Amount is missing\n");
	} else {
		$amount = $_POST['amount'];
		$amount = $amount / 100;
	}
	if (!isset($_POST['orderId'])) {
		$valid = false;
		fwrite($fh, "Error: Order ID is missing\n");
	} else {
		$orderID = $_POST['orderId'];
	}
	if (!isset($_POST['cardTypeName'])) {
		$valid = false;
		fwrite($fh, "Error: Card Type Name is missing\n");
	} else {
		$cardTypeName = $_POST['cardTypeName'];
	}
	if (!isset($_POST['cardNumberMasked'])) {
		$valid = false;
		fwrite($fh, "Error: Card Number Masked is missing\n");
	} else {
		$cardNumberMasked = $_POST['cardNumberMasked'];
	}
	if (!isset($_POST['cardNumberMasked'])) {
		$valid = false;
		fwrite($fh, "Error: Card Number Masked is missing\n");
	}
	if (!isset($_POST['expMonth'])) {
		$valid = false;
		fwrite($fh, "Error: Card expMonth is missing\n");
	} else {
		$expMonth = $_POST['expMonth'];
	}
	if (!isset($_POST['expYear'])) {
		$valid = false;
		fwrite($fh, "Error: Card expYear is missing\n");
	} else {
		$expYear = $_POST['expYear'];
	}
	if (!isset($_POST['currency'])) {
		$valid = false;
		fwrite($fh, "Error: Currency is missing\n");
	} else {
		$currency = $_POST['currency'];
	}
	if (!isset($_POST['language'])) {
		$valid = false;
		fwrite($fh, "Error: Language is missing\n");
	} else {
		$language = $_POST['language'];
	}
	if (!isset($_POST['merchant'])) {
		$valid = false;
		fwrite($fh, "Error: Merchant is missing\n");
	} else {
		$merchant = $_POST['merchant'];
	}
	
	if ($valid) 
	{
		$stmt = $conn->prepare('INSERT INTO del_user_orders 
								(extUserID, orderID, status, amount, cardTypeName, cardNumberMasked, expMonth, expYear, currency, language, merchant)
								VALUES
								(:extUserID, :orderID, :status, :amount, :cardTypeName, :cardNumberMasked, :expMonth, :expYear, :currency, :language, :merchant)');
		if (isset($stmt)) {
			$result = $stmt->execute(array(':extUserID' => $userExtID, 
							 		   ':orderID' => $orderID, 
							 		   ':status' => $status, 
							 		   ':amount' => $amount, 
							 		   ':cardTypeName' => $cardTypeName, 
							 		   ':cardNumberMasked' => $cardNumberMasked, 
							 		   ':expMonth' => $expMonth, 
							 		   ':expYear' => $expYear,
							 		   ':currency' => $currency,
							 		   ':language' => $language,
							 		   ':merchant' =>  $merchant)
							 		   );
			if (!$result) {
				$headers = 'From: noreply@delicion.no' . "\r\n";
				$email = "superuser@kajohansen.com";
				mail($email, "Order callback failed", "Error:\r\nA user has made an order in our store, but callback db insert failed\r\n" . $link, $headers);	
				fwrite($fh, "Error: DB insert failed\n");
			} 
		}
	}
	
	fwrite($fh, "\n     --END--\n");
	fclose($fh);
	
	$oiRow_arr = array();
	foreach ($_POST as $key => $value) {
		if (preg_match('/^oiRow[0-9][0-9]?/', $key)) {
			array_push($oiRow_arr, $value);
		}
	}
	if (count($oiRow_arr) > 0) {
		insert_line_items($oiRow_arr, $orderID, $conn);
	}
	
	function insert_line_items($array, $orderID, $conn) {
		
		$sql = "INSERT INTO del_line_items (orderID, productID, quantity, price)  VALUES ";
		
		foreach ($array as $product_str) {
/* 			echo($product_str . "<br>"); */
			$product_arr = explode(";", $product_str);
			
			for ($i = 0; $i < count($product_arr); $i++) {
				if ($i == 0) {
/* 					echo("Quantity: " . $product_arr[$i] . "<br>"); */
					$quantity = $product_arr[$i];
				}
				if ($i == 3) {
					$price = $product_arr[$i];
					$price = $price * 1.25;
					$price = $price / 100;
/* 					echo("Price: " . $price . "<br>"); */
				}
				if ($i == 4) {
/* 					echo("Product ID: " . $product_arr[$i] . "<br>"); */
					$productID = $product_arr[$i];
				}
				
				if (isset($quantity) && isset($price) && isset($productID)) {
					$sql .= "('" . $orderID . "', '" . $productID . "', '" . $quantity . "', '" . $price . "'),";
					$quantity = NULL;
					$price = NULL;
					$productID = NULL;
				}
			}
		}
		$sql = rtrim($sql, ",");
		$sql .= ";";
/* 		echo("Final SQL: " . $sql); */
		
		$result = $conn->exec($sql);
		
		if ($result > 0) {
/* 			echo("Line Items insert success!"); */
		}
	}
		
?>







