<a id="back_link" onclick="devDibs.getIndex()">&laquo; Back To Index</a>
<a id="receipt_back_link" style="display:none;float:left;margin-top: 50px;margin-left: 5px;" href="http://mserve.kajohansen.com/dibs/">&laquo; Back To Index</a>
<div id="login_button"></div>	
<div id="music_button"></div>	
<div id="shop_button"></div>

<div id="mini_cart_div">
	<img src="images/MiniShop.svg" />
	<span>---- summary ----</span><br />
	<span id="status_span">Basket is empty</span>
	<span id="price_span">0</span>&nbsp;&euro;<br /><br />
	<button id="edit_basket_button" onclick="devDibs.editBasket()">Edit</button>
	<button id="checkout_button" onclick="devDibs.displayLoginPanel()">Log in</button>
	<div id="edit_basket_div"></div>
</div>