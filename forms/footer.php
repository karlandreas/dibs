<select id="categorySelect" onchange="devDibs.getProductCategory(event)">
	<option default value="0">Select a product category</option>
	<option value="headwear">Headwear</option>
	<option value="hoodies">Hoodies</option>
	<option value="duffer">Zipped Hoodies</option>
	<option value="sweaters">Knitted sweaters</option>
	<option value="tshirts-b">Tshirts-boys</option>
	<option value="shoes">Shoes</option>
	<option value="shirts-b">Shirts</option>
	<option value="pique">Piques</option>
	<option value="jumpers">Jumpers</option>
</select>
<div id="login_info_div">
	<span>-- User not logged in --</span><br />
	<?php 
	if (isset($_SESSION['d_user'])) {
		echo($_SESSION['first_name'] . "<br>");	
	}
	?>
</div>