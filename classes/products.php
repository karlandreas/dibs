<?php
	require_once("../includes/connection.php");
	
	if (isset($_POST['category'])) {
		$category = $_POST['category'];
	} else {
		echo("Category is missing\n");
	}
	
	if (isset($category)) {
		$sql = "SELECT * FROM del_products WHERE category='" . $category . "'";

		foreach($conn->query($sql) as $item) 
		{
			$tmp_price = $item['price'];
			$price = $tmp_price * 1.25;
			$div_height = (int)$item['prod_img_height'] + 13;
			echo("<div class='product' style='height: " . $div_height . "px;'>");
			echo("<img class='product_img' src='" . $item['image'] . "' />");
			echo("<p>" . $item['name'] . "</p>");
			echo("<p>Price : " . $price . "&euro;</p>");
			echo("<button onclick=\"devDibs.addToBasket(event,'"  . $item['category'] . "')\">Add To Shopping Basket</button>");
			echo("<br><br><a style='text-decoration:underline;cursor:pointer;' onclick='devDibs.getProductID(" . $item['productID'] . ")'>View More &raquo;</a>");
			echo("<p style='opacity:0;'>" . $item['productID'] . "</p>");
			echo("</div>");
		}
	}
	
?>