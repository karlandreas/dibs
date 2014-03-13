<?php
	require_once("../includes/connection.php");
	$logfile = getcwd() . "/../log/logfile.txt";
	$HmacKey = "6f2d303f5a325f4744427d35342c515a66384f7864702e5a316b43377b322d70485236717d264a25472c446844763a734d357b4761562945462a442e4d533837";
	$dibsPostUrl = "https://sat1.dibspayment.com/dibspaymentwindow/entrypoint";
	
	function createMessage($formKeyValues) {
		$string = "";
		if (is_array($formKeyValues)) {
			ksort($formKeyValues);                              // Sort the posted values by key
			foreach ($formKeyValues as $key => $value) {
				if ($key != "MAC") {                            // Don't include the MAC in the calculation of the MAC.
					if (strlen($string) > 0) $string .= "&";
					$string .= "$key=$value";                   // create string representation
				}
			}
			return $string;
		} else {
			return "An array must be used as input!";
		}
	}
	
	// convert from a hexadecimal to a string.
	function hextostr($hex) {
		$string = "";
		foreach (explode("\n", trim(chunk_split($hex, 2))) as $h) {
			$string .= chr(hexdec($h));
		}
		return $string;
	}
	
	function calculateMac($formKeyValues, $HmacKey, $logfile = null) { // The $logfile is optional.

		if (is_array($formKeyValues)) {
			$messageToBeSigned = createMessage($formKeyValues);
			// Calculate the MAC.
			$MAC = hash_hmac("sha256", $messageToBeSigned, hextostr($HmacKey));
			
			if ($logfile) { // Following is only relevant if you wan't to log the calculated MAC to a log file.
				$fp = fopen($logfile, 'a') or exit("Can't open $logfile!");
				fwrite($fp, "messageToBeSigned: " . $messageToBeSigned . PHP_EOL . " HmacKey: " . $HmacKey . PHP_EOL . " generated MAC: " . $MAC . PHP_EOL);
				if (isset($formKeyValues["MAC"]) && $formKeyValues["MAC"] != "")
					fwrite($fp, " posted MAC:    " . $formKeyValues["MAC"] . PHP_EOL);
			}
			return $MAC;
		
		} else {
		  die("Form key values must be given as an array");
		}
	}
	
	// Starts here
	$formKeyValues = $_POST;
/*
	echo("<pre>");
		print_r($formKeyValues);
	echo("</pre>");
*/
	// check that the prices are correct and add the total amount
	$total_amount = 0;
	foreach($formKeyValues as $key => $value) 
	{
		if (preg_match("/^oiRow[0-9]+/", $key)) 
		{
			$product_arr = explode(";", $value);
			$productID = $product_arr[4];
			$productPrice_arr = str_split($product_arr[3]);
			array_splice($productPrice_arr, count($productPrice_arr) - 2, 0, ".");
			$productPrice = implode("", $productPrice_arr);
			$sql = "SELECT * FROM del_products WHERE productID='" . $productID . "' LIMIT 1";
			
			foreach($conn->query($sql) as $item) 
			{
				if ($item['price'] != $productPrice) {
					/* echo($key . " = " . $value . "<br />"); */
					/* echo("Got price: " . $productPrice . "<br />Is price : " . $item['price'] . "<br />"); */
					array_splice($product_arr, 3, 1, $item['price']);
					$new_product = implode(";", $product_arr);
					$new_product2 = str_replace(".", "", $new_product);
					/* echo($key . " = " . $new_product2);	 */
					$formKeyValues[$key] = $new_product2;
				}
				$tmp_price = $item['price'];
				$total_amount += str_replace(".", "", $tmp_price);
			}
		}
	}	
	$formKeyValues['amount'] = $total_amount * 1.25;
/*
	echo("<pre>");
		print_r($formKeyValues);
	echo("</pre>");
*/
	
	
	// add MAC key to form values
	if ($MAC = calculateMac($formKeyValues, $HmacKey, $logfile)) {
		$formKeyValues['MAC'] = $MAC;
	}
	
	// create hidden form on page
	echo "<form method='post' action=" . $dibsPostUrl . " accept-charset='UTF-8' style='visibility:hidden;'>" . PHP_EOL;
		foreach ($formKeyValues as $key => $value) {
			echo '  <input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
		}
		echo "<input id='submit_button' type='submit' value='Submit' />" . PHP_EOL;
	echo '</form>' . PHP_EOL;

	
	// submit the form
	echo "<script>document.getElementById('submit_button').click();</script>";
	
	
?>