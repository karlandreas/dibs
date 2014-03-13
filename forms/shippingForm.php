<?php
	require_once('../includes/connection.php');
	
	$valid = true;
	$error = "";
	if (isset($_POST['id'])) {
/* 		echo("ID: " . $_POST['id']); */
		$id = $_POST['id'];
	}
	if (isset($_POST['billingFirstName'])) {
/* 		echo("billingFirstName: " . $_POST['billingFirstName']); */
		$shippingFirstName = $_POST['billingFirstName'];
	} else {
		$shippingFirstName = "";
		$error .= "No first name<br>";
		$valid = false;
	}
	if (isset($_POST['billingLastName'])) {
/* 		echo("billingLastName: " . $_POST['billingLastName']); */
		$shippingLastName = $_POST['billingLastName'];
	} else {
		$shippingLastName = "";
		$error .= "No last name<br>";
		$valid = false;
	}
	if (isset($_POST['billingAddress'])) {
/* 		echo("billingAddress: " . $_POST['billingAddress']); */
		$shippingAddress = $_POST['billingAddress'];
	} else {
		$shippingAddress = "";
		$error .= "No address<br>";
		$valid = false;
	}
	if (isset($_POST['billingAddress2'])) {
/* 		echo("billingAddress2: " . $_POST['billingAddress2']); */
		if ($_POST['billingAddress2'] == 'null') {
			$shippingAddress2 = "";
		} else {
			$shippingAddress2 = $_POST['billingAddress2'];
		}
	} else {
		$shippingAddress2 = "";
	}
	if (isset($_POST['billingPostalCode'])) {
/* 		echo("billingPostalCode: " . $_POST['billingPostalCode']); */
		$shippingPostalCode = $_POST['billingPostalCode'];
	} else {
		$shippingPostalCode = "";
		$error .= "No Postal Code<br>";
		$valid = false;
	}
	if (isset($_POST['billingPostalPlace'])) {
/* 		echo("billingPostalPlace: " . $_POST['billingPostalPlace']); */
		$shippingPostalPlace = $_POST['billingPostalPlace'];
	} else {
		$shippingPostalPlace = "";
		$error .= "No Postal Place<br>";
		$valid = false;
	}
?>
<?php if ($valid): ?>
<div id="shippingForm">
	<table id="shippingTable" style="margin-left:20px;">
	<thead>
		<tr>
			<th colspan="2">Enter Shipping info</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><label>Same as Billing:</label></td>
			<td>
				<input type="checkbox" class="largeInput" onclick="setEditableSF()" />
			</td>
		</tr>
		<tr>
			<td><label>First Name(s):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingFirstName"
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   onkeyup="sfKeyUp()" 
								   value="<?php echo($shippingFirstName) ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Last Name(s):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingLastName"
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   onkeyup="sfKeyUp()" 
								   value="<?php echo($shippingLastName) ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Address:</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingAddress" 
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   onkeyup="sfKeyUp()" 
								   value="<?php echo($shippingAddress) ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Address 2(optional):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingAddress2" 
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   value="<?php echo($shippingAddress2) ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Postal Code:</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingPostalCode" 
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   onkeyup="sfKeyUp()" 
								   value="<?php echo($shippingPostalCode) ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Postal Place:</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="shippingPostalPlace" 
								   onfocus="sf_field_focus()" 
								   onblur="sf_field_blur()" 
								   onkeydown="sf_key_enter()" 
								   onkeyup="sfKeyUp()" 
								   value="<?php echo($shippingPostalPlace) ?>" 
								   />
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input type="button" value="Continue to payment" 
									 id="sfContinueButton" 
									 onclick="devDibs.sendCartToDIBS()" 
									 />
			</td>
		</tr>
	</tfoot>
</table>
</div>
<!-- <script src="../scripts/form_functions_shipping.js" ></script> -->
<?php else: ?>
	<h1>An Error occurred...</h1>
	<?php echo($error); ?>
<?php endif; ?>