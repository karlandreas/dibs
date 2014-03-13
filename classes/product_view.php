<?php
	require_once('../includes/connection.php');

	if (isset($_GET['productID'])) {
		$productID = $_GET['productID'];
	} else {
		echo("Missing Product ID\n");
	}
	
	if (isset($productID)) 
	{
		$sql = "CALL get_product_view(" . $productID . ")";
		foreach($conn->query($sql) as $item) {
			$tmp_price = $item['price'];
			$price = $tmp_price * 1.25;
			$div_height = (int)$item['prod_img_height'] + 13;
			echo("<div class='product' style='height: " . $div_height . "px;'>");
			echo("<img class='product_img' src='" . $item['image'] . "' />");
			echo("<p>" . $item['name'] . "</p>");
			echo("<p>Price : " . $price . "&euro;</p>");
			echo("<select>");
			echo("<option>Select color</option>");
			echo( build_options($item) );
			echo("</select><br><br>");
			echo("<button onclick=\"devDibs.addToBasket(event,'"  . $item['category'] . "')\">Add To Shopping Basket</button>");
			
			echo("<p style='opacity:0;'>" . $item['productID'] . "</p>");
			echo("</div>");
		}
	}
	
	function build_options($item) 
	{
		$options = "";
		if (isset($item['purple'])) {
			if ($item['purple'] == 1) {
				$options .= "<option selected>purple</option>";
			} else {
				$options .= "<option>purple</option>";
			}
		}
		if (isset($item['green'])) {
			if ($item['green'] == 1) {
				$options .= "<option selected>green</option>";
			} else {
				$options .= "<option>green</option>";
			}
		}
		if (isset($item['dark_green'])) {
			if ($item['dark_green'] == 1) {
				$options .= "<option selected>dark green</option>";
			} else {
				$options .= "<option>dark green</option>";
			}
		}
		if (isset($item['turquoise'])) {
			if ($item['turquoise'] == 1) {
				$options .= "<option selected>turquoise</option>";
			} else {
				$options .= "<option>turquoise</option>";
			}
		}
		if (isset($item['dark_gray'])) {
			if ($item['dark_gray'] == 1) {
				$options .= "<option selected>dark gray</option>";
			} else {
				$options .= "<option>dark gray</option>";
			}
		}
		if (isset($item['gray'])) {
			if ($item['gray'] == 1) {
				$options .= "<option selected>gray</option>";
			} else {
				$options .= "<option>gray</option>";
			}
		}
		if (isset($item['light_gray'])) {
			if ($item['light_gray'] == 1) {
				$options .= "<option selected>light gray</option>";
			} else {
				$options .= "<option>light gray</option>";
			}
		}
		if (isset($item['dark_blue'])) {
			if ($item['dark_blue'] == 1) {
				$options .= "<option selected>dark blue</option>";
			} else {
				$options .= "<option>dark blue</option>";
			}
		}
		if (isset($item['blue'])) {
			if ($item['blue'] == 1) {
				$options .= "<option selected>blue</option>";
			} else {
				$options .= "<option>blue</option>";
			}
		}
		if (isset($item['light_blue'])) {
			if ($item['light_blue'] == 1) {
				$options .= "<option selected>light blue</option>";
			} else {
				$options .= "<option>light blue</option>";
			}
		}
		if (isset($item['pink'])) {
			if ($item['pink'] == 1) {
				$options .= "<option selected>pink</option>";
			} else {
				$options .= "<option>pink</option>";
			}
		}
		if (isset($item['light_pink'])) {
			if ($item['light_pink'] == 1) {
				$options .= "<option selected>light pink</option>";
			} else {
				$options .= "<option>light pink</option>";
			}
		}
		if (isset($item['light_beige'])) {
			if ($item['light_beige'] == 1) {
				$options .= "<option selected>light beige</option>";
			} else {
				$options .= "<option>light beige</option>";
			}
		}
		if (isset($item['beige'])) {
			if ($item['beige'] == 1) {
				$options .= "<option selected>beige</option>";
			} else {
				$options .= "<option>beige</option>";
			}
		}
		if (isset($item['dark_beige'])) {
			if ($item['dark_beige'] == 1) {
				$options .= "<option selected>dark beige</option>";
			} else {
				$options .= "<option>dark beige</option>";
			}
		}
		if (isset($item['light_red'])) {
			if ($item['light_red'] == 1) {
				$options .= "<option selected>light red</option>";
			} else {
				$options .= "<option>light red</option>";
			}
		}
		if (isset($item['red'])) {
			if ($item['red'] == 1) {
				$options .= "<option selected>red</option>";
			} else {
				$options .= "<option>red</option>";
			}
		}
		if (isset($item['brown'])) {
			if ($item['brown'] == 1) {
				$options .= "<option selected>brown</option>";
			} else {
				$options .= "<option>brown</option>";
			}
		}
		if (isset($item['pattern'])) {
			if ($item['pattern'] == 1) {
				$options .= "<option selected>pattern</option>";
			} else {
				$options .= "<option>pattern</option>";
			}
		}
		
		
		return $options;
	}
?>