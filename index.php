<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head lang="en">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link href='https://fonts.googleapis.com/css?family=Strait' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="css/shop.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/panels.css" type="text/css" media="screen" />
	</head>
	<body>
		<div id="site">
		<header>
			<?php include('forms/header.php'); ?>
		</header>
		<div id="main_div">
			
		</div>
		<footer>
			<?php include('forms/footer.php'); ?>
		</footer>
		<?php include('forms/checkoutForm.html'); ?>
		</div>
	</body>
	<script src="scripts/DevDibs.js" type="text/javascript"></script>
	<script src="scripts/DevDibsProto.js" type="text/javascript"></script>

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
		devDibs.getIndex();
	</script>
	<?php
		if (isset($_POST['status'])) {
			if ($_POST['status'] == "CANCELLED") {
				echo("<script>devDibs.displayCancelPage();</script>");
				$_POST = NULL;
			}
		}
	?>
</html>

