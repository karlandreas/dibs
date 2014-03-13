<?php
	require_once('../includes/connection.php');
	
	if (isset($_POST['id'])) {
		/* echo("ID: " . $_POST['id']); */
		$id = $_POST['id'];
		$stmt = $conn->prepare('SELECT * FROM del_user_billing_address WHERE extUserID=:id LIMIT 1');
		$result = $stmt->execute(array(':id' => $id));
		
		if ($result) {
			$result = $stmt->fetchAll();
			
			if ($result == array()) {
				$result = array(
							array(
								'billingFirstName' => "",
								'billingLastName' => "",
								'billingAddress' => "",
								'billingAddress2' => "",
								'billingPostalCode' => "",
								'billingPostalPlace' => "",
								'billingMobile' => "",));
			}
		}

	} else {
		$noid = true;
	}
?>


<?php if (isset($noid)): ?>

<h1>Error: No id or not logged in</h1>

<?php else: ?>

<div id="billingDiv" style="margin-left:20px;">
<table id="billingTable" class="largeInput" style="float:left;">
	<thead>
		<tr>
			<th colspan="2">Enter Billing info</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><label>First Name(s):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput"
								   name="billingFirstName" 
								   onfocus="devDibs.billingFormFieldFocus()"
								   onblur="devDibs.billingFormFieldBlur()"
								   onkeydown="devDibs.billingFormKeyEnter()"
								   value="<?php echo($result[0]['billingFirstName']); ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Last Name(s):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="billingLastName" 
								   onfocus="devDibs.billingFormFieldFocus()"
								   onblur="devDibs.billingFormFieldBlur()"
								   onkeydown="devDibs.billingFormKeyEnter()"
								   value="<?php echo($result[0]['billingLastName']); ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Address:</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="billingAddress" 
								   onfocus="devDibs.billingFormFieldFocus()"
								   onblur="devDibs.billingFormFieldBlur()"
								   onkeydown="devDibs.billingFormKeyEnter()"
								   value="<?php echo($result[0]['billingAddress']); ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Address 2(optional):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="billingAddress2" 
								   onfocus="devDibs.billingFormFieldFocus()"
								   onblur="devDibs.billingFormFieldBlur()"
								   onkeydown="devDibs.billingFormKeyEnter()"
								   value="<?php echo($result[0]['billingAddress2']); ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Postal Code:</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="billingPostalCode" 
								   onfocus="devDibs.billingFormFieldFocus()"
								   onblur="devDibs.billingFormFieldBlur()"
								   onkeydown="devDibs.billingFormKeyEnter()"
								   value="<?php echo($result[0]['billingPostalCode']); ?>" 
								   />
			</td>
		</tr>
		<tr>
			<td><label>Postal Place:</label></td>
				<td><input type="text" size="30" 
									   class="largeInput" 
									   name="billingPostalPlace" 
									   onfocus="devDibs.billingFormFieldFocus()"
									   onblur="devDibs.billingFormFieldBlur()"
									   onkeydown="devDibs.billingFormKeyEnter()"
									   value="<?php echo($result[0]['billingPostalPlace']); ?>" 
									   />
			</td>
		</tr>
		<tr>
			<td><label>Mobile (optional):</label></td>
			<td>
				<input type="text" size="30" 
								   class="largeInput" 
								   name="billingMobile" 
								   onfocus="devDibs.billingFormFieldFocus()" 
								   onblur="devDibs.billingFormFieldBlur()" 
								   onkeydown="devDibs.billingFormKeyEnter()" 
								   value="<?php echo($result[0]['billingMobile']); ?>" 
								  />
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>&nbsp;</td>
			<td><input type="button" value="Continue" 
									 id="bfContinueButton" 
									 onclick="devDibs.getShippingInfo()" 
									 />
			</td>
		</tr>
	</tfoot>
</table>
</div>
<?php endif; ?>
