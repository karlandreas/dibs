<?php
	session_start();
	
	function getProductString($oiRow) {
		$product = "";
		$oi_arr = explode(";", $oiRow);
		
		for ($i = 0; $i < count($oi_arr); $i++) {
			if ($i == 0) {
				$product .= $oi_arr[$i] . ", ";
			}
			if ($i == 2) {
				$product .= $oi_arr[$i] . ", ";
			}
		}
		return $product;
	}
	
	function getProductPrice($oiRow) {
		$price = "";
		$vat = "";
		$oi_arr = explode(";", $oiRow);
		
		for ($i = 0; $i < count($oi_arr); $i++) {
			if ($i == 3) {
				$price = $oi_arr[$i] / 100;
			}
			if ($i == 5) {
				$vat_tmp = $oi_arr[$i] + 10000;
				$vat = $vat_tmp / 10000;
			}
		}
		return $price * $vat;
	}
	if (!isset($_SESSION['date'])) {
		$_SESSION['date'] = strftime("%Y-%m-%d %H:%M:%S", time() + (3600 * 2));
	}
?>
<!DOCTYPE html>
<html>
	<head lang="en">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link href='https://fonts.googleapis.com/css?family=Strait' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/shop.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/panels.css" type="text/css" media="screen" />
	</head>
	<body>
	<div id="site">
		<header>
			<?php include('forms/header.php'); ?>
		</header>
		<div id="main_div" style="padding-left:50px;">
			<?php
				echo("<h1>Thank you for your order, and here is your receipt:</h1>");
				// write recipient
				echo("<strong>KAjohansen</strong><br />Vibes gate 23<br />0356 OSLO<br />NORWAY
					  <strong style='float:right;'>" . $_SESSION['date'] . "</strong><br /><hr /><br /><br />");
				
				// write receipt
				if (isset($_POST['status']) && $_POST['status'] == "ACCEPTED") {
					echo("<strong>transaction ID: </strong>" . $_POST['transaction'] . "<br />");
					echo("<strong>order ID: </strong>" . $_POST['orderId'] . "<br />");
					echo("<strong>Card: </strong>" . $_POST['cardTypeName'] . " - " . $_POST['cardNumberMasked'] ."<br />");
					echo("<br /><b>Shipping Address:</b><br />");
					echo($_POST['billingFirstName'] . " " . $_POST['billingLastName'] . "<br />");
					echo($_POST['billingAddress'] . "<br />");
					echo($_POST['billingPostalCode'] . " " . $_POST['billingPostalPlace'] . "<br />");
				}
				
				if (isset($_POST['currency'])) {
					if ($_POST['currency'] == 978) {
						$currency = "&euro;";
					}
				}
				echo("<br /><b>Products: </b>");
				foreach ($_POST as $key => $value) 
				{
					if (preg_match('/^oiRow[0-9][0-9]?/', $key)) {
						$product = getProductString($value);
						$product_price = getProductPrice($value);
						echo("<br />" . $product . $product_price . $currency);
					}
				}
			?>
			<button style="float:right;" onclick="window.print()">Print Receipt</button>
		</div>
		<footer>
			<?php include('forms/footer.php'); ?>
		</footer>
	</div>
	<script src="scripts/DevDibs.js" type="text/javascript"></script>
	<script src="scripts/DevDibsProto.js" type="text/javascript"></script>
	<script>
		localStorage.clear();
		document.getElementById('receipt_back_link').style.display = 'block';
		document.getElementById('categorySelect').style.display = 'none';
	</script>
	</body>
	<script>
		<?php 
			if (isset($_SESSION['userID'])) {
				echo("devDibs.setLoggedIn('" . $_SESSION['userID'] . "');");
			}  else {
				echo("devDibs.setBasketSummary();");
			}
			if (isset($_SESSION['g_user'])) {
				echo("devDibs.setLoggedIn('" . $_SESSION['g_user']['uid'] . "');");
			}
			if (isset($_SESSION['f_user'])) {
				echo("devDibs.setLoggedIn('" . $_SESSION['f_user']['id'] . "');");
			}
			if (isset($_SESSION['l_user'])) {
				echo("devDibs.setLoggedIn('" . $_SESSION['l_user']['uid'] . "');");
			}
		?>
	</script>
</html>


