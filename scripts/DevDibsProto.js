DevDibs.prototype = {
	
	// Mini Shop functions .....................................................
	
	setBasketSummary: function() {
		
		this.price_span.innerHTML = (localStorage.priceTotal) ? localStorage.priceTotal : 0;
	
		if (!localStorage.itemCount || localStorage.itemCount == 0) {
			this.status_span.innerHTML = "Basket is empty ";
			if (this.userIsLoggedIn) {
				this.checkout_button.setAttribute('disabled', "true");
			} else {
				this.checkout_button.removeAttribute('disabled');
			}
			localStorage.priceTotal = 0;
		} else if (localStorage.itemCount == 1) {
			this.status_span.innerHTML = "Basket contains : " + localStorage.itemCount + " item";
			this.checkout_button.removeAttribute('disabled');
		} else {
			this.status_span.innerHTML = "Basket contains : " + localStorage.itemCount + " items";
			this.checkout_button.removeAttribute('disabled');
		}
	},
	
	addToBasket: function(event, category) {
		if (this.miniShopIsEditing) {
			this.closeEditBasketDiv();
		}
		
		var priceArray = event.target.parentElement.childNodes[2].innerHTML.match(/[0-9\.]/g);
		var price = priceArray.join("");
		
		var obj = {
			"id" : event.target.parentElement.childNodes[7].innerHTML,
			"category" : category,
			"url" : event.target.parentElement.childNodes[0].src,
			"name" : event.target.parentElement.childNodes[1].innerHTML,
			"price" : price,
		}
	
		localStorage.itemCount = (localStorage.itemCount) ? Number(localStorage.itemCount) + 1 : 1;
		
		var item_array = (localStorage.Items) ? JSON.parse(localStorage.Items) : new Array();
		item_array.push(obj);
		localStorage.setItem("Items", JSON.stringify(item_array));
	
		var newPrice = new Number(localStorage.priceTotal) + new Number(price);
		if (String(newPrice).match(/\.[0-9]$/)) {
			newPrice = newPrice + "0";
		}
		localStorage.priceTotal = newPrice;
	
		this.setBasketSummary();	
	},
	
	removeFromBasket: function(index) {
		var item_array = JSON.parse(localStorage.Items);
		for (var i = 0; i < item_array.length; i++) {
			if (index == i) {
				localStorage.priceTotal = new Number(localStorage.priceTotal) - new Number(item_array[i].price);
				this.price_span.innerHTML = localStorage.priceTotal;
				localStorage.itemCount = Number(localStorage.itemCount) - 1;
				item_array.splice(i, 1);
				localStorage.setItem("Items", JSON.stringify(item_array));
			}
		}
		this.setBasketSummary();
		item_array = JSON.parse(localStorage.Items);
		this.populateMiniBasket(item_array);
		if (item_array.length == 0) {
			this.closeEditBasketDiv();
		}
	},
	
	closeEditBasketDiv: function() {
		this.miniShopIsEditing = false;
		this.edit_basket_button.innerHTML = "Edit"
		this.edit_basket_div.innerHTML = "";
		setTimeout(function() {
			this.mini_cart_div.style.boxShadow = "";
		}, 900);
		this.mini_cart_div.style.height = '80px';
	},
	
	populateMiniBasket: function(item_array) {
		this.edit_basket_div.innerHTML = "<br /><span>--- Products in basket</span><hr />";
		this.mini_cart_div.style.height = (item_array.length * 78) + 130 + "px";
		for (var i = 0; i < item_array.length; i++) {
			this.edit_basket_div.innerHTML += "<div><img src='" + item_array[i].url + "' /> \
			<span>" + item_array[i].name + 
			"</span><br /><span style='font-size:10pt;'>" + item_array[i].price + 
			" &euro;</span><button onclick='devDibs.removeFromBasket(" + i + ")'>Delete</button><br /></div><br />";
			this.edit_basket_div.innerHTML += "<hr />";
		}
	},
	
	editBasket: function() {
		if (!this.miniShopIsEditing) {
			var item_array = (localStorage.Items) ? JSON.parse(localStorage.Items) : new Array();
			this.miniShopIsEditing = true;
			this.edit_basket_button.innerHTML = "Done";
			this.mini_cart_div.style.boxShadow = "-1px 1px 5px black";
			this.mini_cart_div.style.height = (item_array.length * 78) + 130 + "px";
			setTimeout(function() 
			{
				devDibs.populateMiniBasket(item_array);
			}, 600);
		} else {
			this.closeEditBasketDiv();
		}
	},
	
	postProductCategoryRequest: function(category) {
		this.xmlHTTPrequest.open('POST', "http://mserve.kajohansen.com/dibs/classes/products.php", true);
		var data = "category=" + category;
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) { 
				devDibs.main_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
				devDibs.back_link.style.display = "block";
			}
		}
	},
	
	getProductCategory: function(event) {
	for (var i = 0; i < event.target.options.length; i++) {
		if (event.target.options[i].selected == true) {
			this.postProductCategoryRequest(event.target.options[i].value);
		}
	}
},
	
	getIndex: function() {
		this.xmlHTTPrequest.open('GET', "forms/index.html", true);
		this.xmlHTTPrequest.send(null);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) { 
				devDibs.main_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
			}
		}
		this.back_link.style.display = "none";
	},
	
	
	// Login Panels setup ..........................................................
	setupLoginPanels: function() {
		
		/* Login Background Div */
		this.panels_background_div.setAttribute("id", "panel_background_div");
		this.panels_background_div.appendChild(this.panels_container_div);
		
		/* Login Container Div */
		this.panels_container_div.setAttribute("class", "panels_container");
		this.panels_container_div.appendChild(this.panels_menu_div);
		this.panels_container_div.appendChild(this.panels_slider_div);
			
		/* Panels Menu Div */ 	
	 	this.panels_menu_div.setAttribute("id", "panel_menu_div");
	 	
		/* Panels Slider Div */
	 	this.panels_slider_div.setAttribute("id", "panel_slider_div");
	 	this.panels_slider_div.setAttribute("class", "panels_slider");
		this.panels_slider_div.appendChild(this.first_panel_div);
		
		/* Panels Close Image */
	 	this.panels_close_img.setAttribute("src", "images/Xclose.svg");
	 	this.panels_close_img.setAttribute("id", "close_img");
		this.panels_close_img.onclick = function() {
			devDibs.site.removeChild(devDibs.panels_background_div);
			if (devDibs.login_result_div.parentNode != null) {
				devDibs.panels_slider_div.removeChild(devDibs.login_result_div);
			}
		}
		this.panels_menu_div.appendChild(this.panels_close_img);
		
		/* Panel Login Div */
		this.first_panel_div.setAttribute("class", "panel");
		this.first_panel_div.style.left = "0px";
		this.first_panel_div.style.backgroundColor = "#8ebca3";
		
		/* Panel Register Div */
		this.second_panel_div.setAttribute("class", "panel");
		this.second_panel_div.style.left = "500px";
		this.second_panel_div.style.backgroundColor = "#ec7ec7";
		this.panels_slider_div.appendChild(this.second_panel_div);
		
		/* Login Span */
	 	this.first_span.setAttribute('class', "slider_span");
	 	this.first_span.innerHTML = "Login";
		this.first_span.onclick = function() {
			if (!devDibs.userIsLoggedIn) {
				devDibs.first_span.className += " active_span";
				devDibs.second_span.className = "slider_span";
				devDibs.panels_slider_div.style.left = "0px";
			}
		}
		this.panels_menu_div.appendChild(this.first_span);
			
		/* register Span */
		this.second_span.setAttribute('class', "slider_span");
	 	this.second_span.innerHTML = "Register";
		this.second_span.onclick = function() {
			if (!devDibs.userIsLoggedIn) {
				devDibs.first_span.className = "slider_span";
				devDibs.second_span.className += " active_span";
				devDibs.panels_slider_div.style.left = "-500px";
				devDibs.getRegisterDiv();
			}
		}
		this.panels_menu_div.appendChild(this.second_span);
		
		/* Login Result Div */
		this.login_result_div.setAttribute("class", "panel");
		this.login_result_div.setAttribute('id', 'login_result_div');
		this.login_result_div.style.left = "500px";
		this.login_result_div.style.backgroundColor = "#8ebca3";
		
		/* Login Result Button */
		this.login_result_button.innerHTML = "Try Again";
		
		/* Register result Div */
		this.register_result_div.setAttribute("class", "panel");
		this.register_result_div.style.left = "1000px";
		this.register_result_div.style.backgroundColor = "#8ebca3";
		
		/* Registration Verified Image */
		this.verified_img.setAttribute("src", 'images/Verified_icon.png');
		this.verified_img.style.height = "100px";
		this.verified_img.style.marginTop = "50px";
		this.verified_img.style.cursor = "pointer";
	},
	
	getRegisterDiv: function() {
		this.xmlHTTPrequest.open('GET', "forms/registerForm.html", true); 
		this.xmlHTTPrequest.send(null);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				if (document.getElementById('login_result_div')) {
					devDibs.panels_slider_div.removeChild(devDibs.login_result_div);
				}
				devDibs.second_panel_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
				var registerForm = document.getElementById("registerForm");
				devDibs.confirmedImages = document.getElementsByClassName("confirmed_img");
				devDibs.unconfirmedImages = document.getElementsByClassName("unconfirmed_img");
				devDibs.evaluatingImages = document.getElementsByClassName("loader_img");
				devDibs.registerFormlabels = registerForm.getElementsByTagName("label");
			}
		}
	},
	
	displayRegistrationResult: function(valid, message) {
		
		if (valid) {
			this.verified_img.onclick = function() {
				devDibs.site.removeChild(devDibs.panels_background_div);
				devDibs.panels_slider_div.style.left = "0px";
			}
			
			this.register_result_div.appendChild(this.verified_img);
		} else {
			this.register_result_div.innerHTML = "<h1>OOps</h1><p>Registration failed..</p><p>" + message + "</p>";
		}
		
		
		this.panels_slider_div.appendChild(this.register_result_div);
		this.panels_slider_div.style.left = "-1000px";
	},
	
	displayLoginResult: function(success, reason) {
		
		if (success) {
			this.login_result_div.innerHTML = "<h3>Login Success!</h3>";
			this.verified_img.onclick = function() {
				devDibs.site.removeChild(devDibs.panels_background_div);
				devDibs.panels_slider_div.removeChild(devDibs.login_result_div);
			}
			this.login_result_div.appendChild(this.verified_img);
		} 
		else {
			this.login_result_div.innerHTML = "<h3>Login Failed</h3>";
			this.login_result_div.innerHTML += "<p>" + reason + "</p>";
			this.login_result_button.onclick = function() {
				devDibs.panels_slider_div.style.left = "0px";
			}
			this.login_result_div.appendChild(this.login_result_button);
		}
		
		this.panels_slider_div.appendChild(this.login_result_div);
		this.panels_slider_div.style.left = "-500px";
	},
	
	displayLoginPanel: function()  {
	
		if (this.miniShopIsEditing) {
			this.closeEditBasketDiv();
		}
		this.first_span.className += " active_span";
		this.site.appendChild(this.panels_background_div);
		this.xmlHTTPrequest.open('GET', "forms/loginForm.php", true); 
		this.xmlHTTPrequest.send(null);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				devDibs.first_panel_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
			}
		}
	},
	
	// Login-Form functions ........................................................
	checkLoginFields: function() {
	
		for (var i = 0; i < this.validLoginFieldArray.length; i++) {
			if (this.validLoginFieldArray[i] == false) {
				this.confirmed = false;
			} else {
				this.confirmed = true;
			}
		}
		if (this.confirmed == true) {
			document.getElementById("login_user_button").removeAttribute("disabled");
		} else {
			document.getElementById("login_user_button").setAttribute("disabled", "true");
		}
		return this.confirmed;
	},
	
	checkUsername: function()  {
	
		var target = (event.target) ? event.target : event.srcElement;
		var value_arr = target.value.split("");
		
		if (value_arr.length > 4 && value_arr.length < 30) {
			target.style.backgroundColor = "lime";
			this.validLoginFieldArray[0] = true;
		} else {
			target.style.backgroundColor = "orange";
		}
		this.checkLoginFields();
	},
	
	checkPassword: function() {
	
		var target = (event.target) ? event.target : event.srcElement;
		var value_arr = target.value.split("");
		
		if (value_arr.length > 4 && value_arr.length < 30) {
			target.style.backgroundColor = "lime";
			this.validLoginFieldArray[1] = true;
		} else {
			target.style.backgroundColor = "orange";
		}
		this.checkLoginFields();
		
		if (event.keyCode == '13') {
			if (this.checkLoginFields()) {
				this.sendLogin();
			}
		}
	},
	
	loginEnter: function() {
		var target = (event.target) ? event.target : event.srcElement;
		target.style.backgroundColor = "orange";
	},
	
	preventDef: function() {
		if(event.preventDefault) {
			event.preventDefault();
		} else {
			event.returnValue = false;
		}
		
		document.getElementById("login_username_field").focus();
	},
	
	setLoggedIn: function(id) {
	
		this.userIsLoggedIn = true;
		this.userID = id;
		this.checkout_button.innerHTML = "Checkout";
		this.checkout_button.onclick = function () {
			devDibs.displayShippingBillingPanel();
		}
		var item_array = (localStorage.Items) ? JSON.parse(localStorage.Items) : new Array();
		if (item_array.length < 1 || item_array == null) {
			this.checkout_button.setAttribute("disabled", "true");
		} else {
			this.setBasketSummary();
			this.populateMiniBasket(item_array);
			this.closeEditBasketDiv();
		}
	
		this.logged_in_span.innerHTML = "-- User logged in --";
		this.logout_button.setAttribute('onclick', 'devDibs.logout()');
		this.logout_button.innerHTML = "Log Out";
		this.login_info_div.replaceChild(this.logged_in_span, this.login_info_div.childNodes[1]);
		this.login_info_div.appendChild(this.logout_button);
	},
	
	sendLogin: function() {
	
		var login_form = document.getElementById("login_form");
		var username = "username=" +  login_form[0].value + "&";
		var password = "password=" + login_form[1].value;
		var data = username + password;
	
		this.xmlHTTPrequest.open('POST', "classes/user_login.php", true);
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				var response_obj = JSON.parse(devDibs.xmlHTTPrequest.responseText);
				if (response_obj.success) {
					devDibs.displayLoginResult(true);
					devDibs.setLoggedIn(response_obj.userExtID);
				} else {
					devDibs.displayLoginResult(false, response_obj.reason);
				}
			}
		}
	},
	
	logout: function() {
		
		window.top.location = "http://mserve.kajohansen.com/dibs/oauthcallback/logout.php";
	},
	
	// Billing - Shipping functions ..........................................................
	updateBillingInfo: function() {
	
		var data = "";
		for (var key in this.billingFormNewFieldsArray) {
			if (this.billingFormNewFieldsArray[key] != "") {
				data += key + "=" + this.billingFormNewFieldsArray[key] + "&";
			}
		}
		data += "id=" + this.userID;
	/* 	console.log(data); */
		
		// This is a new xmlHttpRequest by design DON'T change to this.xmlHTTPRequest!
		var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : ActiveXObject("Microsoft.XMLHTTP");
		xhr.open('POST', "classes/update_user_billing_info.php", true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function() {
			if (xhr.status == 200 && xhr.readyState == 4) {
				console.log("billing update: " + xhr.responseText);
				devDibs.billingFormHasChanged = false;
			} else {
				console.log("billing update failed: " + xhr.responseText);
			}
		}
	},
	
	billingFormFieldFocus: function() {
	
		this.billingFormActiveFieldValue = event.target.value;
	},
	
	billingFormFieldBlur: function() {
		
		if (this.billingFormActiveFieldValue != this.billingFormActiveFieldNewValue) {
			this.billingFormNewFieldsArray[event.target.name] = this.billingFormActiveFieldNewValue;
			this.billingFormHasChanged = true;
		}
		this.billingFormCheckAllFields();
	},
	
	billingFormKeyEnter: function() {
	
		this.billingFormActiveFieldNewValue = event.target.value;
	},
	
	displayShippingBillingPanel: function()  {
	
		if (this.miniShopIsEditing) {
			this.closeEditBasketDiv();
		}
		
		this.first_span.innerHTML = "Billing Info";
		this.second_span.innerHTML = "Shipping Info";
		this.first_span.className += " active_span";
		this.site.appendChild(this.panels_background_div);	
		this.panels_slider_div.style.left = "0px";
		
		var data = "id=" + this.userID;
		
		this.xmlHTTPrequest.open('POST', "forms/billingForm.php", true); 
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				devDibs.first_panel_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
				devDibs.billingFormCheckAllFields();
			}
		}
	},
	
	// Billing-Form functions ........................................................
	billingFormCheckAllFields: function() {
	
		var invalid = false;
		this.tbody = document.getElementById('billingTable').childNodes[3];
		this.billingFormInputElementsList = this.tbody.getElementsByTagName('input');
		var button = document.getElementById('bfContinueButton');
		
		for(var i = 0; i < this.billingFormInputElementsList.length; i++) {
			if (i != 3 && i != 6) {
				if (this.billingFormInputElementsList[i].value == "") {
					invalid = true;
					this.billingFormNewFieldsArray[this.billingFormInputElementsList[i].name] = this.billingFormInputElementsList[i].value;
				} else {
					this.billingFormNewFieldsArray[this.billingFormInputElementsList[i].name] = this.billingFormInputElementsList[i].value;
				}
			} else {
				if (this.billingFormInputElementsList[i].value == "") {
					this.billingFormNewFieldsArray[this.billingFormInputElementsList[i].name] = null;
				} else {
					this.billingFormNewFieldsArray[this.billingFormInputElementsList[i].name] = this.billingFormInputElementsList[i].value;
				}
			}
		}
		if (invalid) {
			button.setAttribute('disabled', 'true');
		} else {
			if (button.hasAttribute('disabled')) {
				button.removeAttribute('disabled');
			}
		}
	},
	
	getShippingInfo: function() {
	
		if (this.billingFormHasChanged ) {
			this.updateBillingInfo();
		}
		
		this.addBillingFormFields();
	
		var data = "";
		for (var key in this.billingFormNewFieldsArray) {
			if (this.billingFormNewFieldsArray[key] != "") {
				data += key + "=" + this.billingFormNewFieldsArray[key] + "&";
			}
		}
		data += "id=" + this.userID;
		
		this.xmlHTTPrequest.open('POST', "forms/shippingForm.php", true); 
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				devDibs.second_panel_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
				setTimeout(function() {
					devDibs.setValidShippingForm();
				}, 500);
			} else {
				devDibs.second_panel_div.innerHTML = "<h1>Connection problem..</h1><p>Please try again</p>";
			}
		}
		
		this.first_span.className = "slider_span";
		this.second_span.className += " active_span";
		this.panels_slider_div.style.left = "-500px";
	},
	
	addBillingFormFields: function() {
		
		for (var key in this.billingFormNewFieldsArray) {
		
			var inputElement = document.createElement('input');
			inputElement.setAttribute('type', 'hidden');
			inputElement.setAttribute('name', key);
			
			if (this.billingFormNewFieldsArray[key] == null || this.billingFormNewFieldsArray[key] == 'null') {
				inputElement.setAttribute('value', "");
			} else {
				inputElement.setAttribute('value', this.billingFormNewFieldsArray[key]);
			}
			
			this.checkout_form.appendChild(inputElement);
			this.checkoutFormFields.push(inputElement);
		}
	},
	
	// Register-Form functions ........................................................
	setSelected: function(event, l) {
		
		this.confirmedImages[l].style.display = "none";
		this.registerFormlabels[l].style.padding = "3px";
		this.registerFormlabels[l].style.borderRadius = "5px";
		this.registerFormlabels[l].style.backgroundColor = "yellow";
		this.evaluatingImages[l].style.display = "block";
		this.unconfirmedImages[l].style.display = "none";
		event.target.style.backgroundColor = "yellow";
		this.checkAllFields();
	},
	
	removeSelected: function(event, l) {
		
		var value_arr = event.target.value.split("");
		
		this.registerFormlabels[l].style.padding = "0px";
		this.registerFormlabels[l].style.borderRadius = "0px";
		this.registerFormlabels[l].style.backgroundColor = "";
		this.evaluatingImages[l].style.display = "none";
		
		if (this.validRegisterFieldArray[l] == true) {
			event.target.style.backgroundColor = "lime";
			this.confirmedImages[l].style.display = "block";
		}
		if (this.validRegisterFieldArray[l] == false) {
			event.target.style.backgroundColor = "red";
			this.unconfirmedImages[l].style.display = "block";
		}
		if (value_arr.length < 1) {
			event.target.style.backgroundColor = "";
			this.unconfirmedImages[l].style.display = "none";
		}
		this.checkAllFields();
	},
	
	checkAllFields: function() {
		
		var confirmed = true;
		for (var i = 0; i < this.validRegisterFieldArray.length; i++) {
			if (this.validRegisterFieldArray[i] == false) {
				confirmed = false;
			}
		}
		if (confirmed == true) {
			document.getElementById("regUsrSubmBtn").removeAttribute("disabled");
		} else {
			document.getElementById("regUsrSubmBtn").setAttribute("disabled", "true");
		}
	},
	
	usernameChange: function(event) {
	
		var value_arr = event.target.value.split("");
		
		if (value_arr.length > 4 && value_arr.length < 30) {
			
			event.target.style.backgroundColor = "yellow";
			var requestString = "classes/username_check.php?username=" + event.target.value;
			
			this.xmlHTTPrequest.open('GET', requestString, true);
			this.xmlHTTPrequest.send();
			this.xmlHTTPrequest.onreadystatechange = function() {
				if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				
					var gotUsername = devDibs.xmlHTTPrequest.responseText.replace(/ /g, '');
				
					if (gotUsername.match(/^true/)) {
						event.target.style.backgroundColor = "red";
						devDibs.validRegisterFieldArray[0] = false;
						devDibs.confirmedImages[0].style.display = "none";
						devDibs.evaluatingImages[0].style.display = "none";
						devDibs.unconfirmedImages[0].style.display = "block";
					}
					if (gotUsername.match(/^false/)) {
						event.target.style.backgroundColor = "lime";
						devDibs.validRegisterFieldArray[0] = true;
						devDibs.confirmedImages[0].style.display = "block";
						devDibs.evaluatingImages[0].style.display = "none";
						devDibs.unconfirmedImages[0].style.display = "none";
					}
				}
			}
		} else {
			event.target.style.backgroundColor = "orange";
			this.confirmedImages[0].style.display = "none";
			this.evaluatingImages[0].style.display = "block";
		}
		this.checkAllFields();
	},
	
	emailChange: function(event) {
	
		var value_arr = event.target.value.split("");
	
		if (value_arr.length > 7 && value_arr.length < 50) {
			if (event.target.value.match(/.+@.+\...+/)) {
				event.target.style.backgroundColor = "lime";
				this.confirmedImages[1].style.display = "block";
				this.evaluatingImages[1].style.display = "none";
				this.validRegisterFieldArray[1] = true;
			} else {
				this.validRegisterFieldArray[1] = false;
			}
		} else {
			if (value_arr.length < 1) {
				event.target.style.backgroundColor = "yellow";
				this.confirmedImages[1].style.display = "none";
				this.evaluatingImages[1].style.display = "block";
			} else {
				event.target.style.backgroundColor = "orange";
				this.confirmedImages[1].style.display = "none";
				this.evaluatingImages[1].style.display = "block";
			}
			
			this.validRegisterFieldArray[1] = false;
		}
		this.checkAllFields();
	},
	
	nameChange: function(event, l) {
		
		var value_arr = event.target.value.split("");
		
		if (value_arr.length > 2 && value_arr.length < 120) {
			this.validRegisterFieldArray[l] = true;
			event.target.style.backgroundColor = "lime";
			this.confirmedImages[l].style.display = "block";
			this.evaluatingImages[l].style.display = "none";
		}
		if (value_arr.length > 119) {
			event.target.style.backgroundColor = "red";
			this.validRegisterFieldArray[l] = false;
			this.confirmedImages[l].style.display = "none";
			this.evaluatingImages[l].style.display = "block";
		}
		if (value_arr.length < 2) {
			event.target.style.backgroundColor = "yellow";
			this.validRegisterFieldArray[l] = false;
			this.evaluatingImages[l].style.display = "block";
			this.confirmedImages[l].style.display = "none";
		}
		this.checkAllFields();
	},
	
	setPassword: function(event) {
		
		var value_arr = event.target.value.split("");
		
		if (value_arr.length > 5) {
			this.tmpPassword = event.target.value;
			event.target.style.backgroundColor = "lime";
			this.validRegisterFieldArray[4] = true;
			this.confirmedImages[4].style.display = "block";
			this.evaluatingImages[4].style.display = "none";
		} else if(value_arr.lang < 1) {
			event.target.style.backgroundColor = "yellow";
			this.validRegisterFieldArray[4] = false;
			this.evaluatingImages[4].style.display = "block";
			this.confirmedImages[4].style.display = "none";
		} else {
			event.target.style.backgroundColor = "orange";
			this.validRegisterFieldArray[4] = false;
			this.evaluatingImages[4].style.display = "block";
			this.confirmedImages[4].style.display = "none";
		}
	},
	
	validatePassword: function(event) {
	
		var value_arr = event.target.value.split("");
		
		if (value_arr.length > 5) {
			if (this.tmpPassword == event.target.value) {
				event.target.style.backgroundColor = "lime";
				this.validRegisterFieldArray[5] = true;
				this.confirmedImages[5].style.display = "block";
				this.evaluatingImages[5].style.display = "none";
			} else {
				event.target.style.backgroundColor = "orange";
				this.confirmedImages[5].style.display = "none";
				this.evaluatingImages[5].style.display = "block";
				this.validRegisterFieldArray[5] = false;
			}
		} else if(value_arr.lang < 1) {
			event.target.style.backgroundColor = "yellow";
			this.validRegisterFieldArray[5] = false;
			this.evaluatingImages[5].style.display = "block";
			this.confirmedImages[5].style.display = "none";
		} else {
			event.target.style.backgroundColor = "orange";
			this.validRegisterFieldArray[5] = false;
			this.evaluatingImages[5].style.display = "block";
			this.confirmedImages[5].style.display = "none";
		}
		this.checkAllFields();
	},
	
	sendRegistrationForm: function() {
	
		var theForm = document.getElementById("registerForm");
		var username = "username=" +  theForm[0].value + "&";
		var email = "email=" + theForm[1].value + "&";
		var firstNames = "firstNames=" + theForm[2].value + "&";
		var lastNames = "lastNames=" + theForm[3].value + "&";
		var password = "password=" + theForm[4].value;
		var data = username + email + firstNames + lastNames + password;
		
		this.xmlHTTPrequest.open('POST', "classes/register_user.php", true);
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				var response_text = devDibs.xmlHTTPrequest.responseText;
				if (response_text.match(/^success!/)) {
					console.log("display success message");
					devDibs.displayRegistrationResult(true);
				} else {
					console.log("display error message");
					devDibs.displayRegistrationResult(false, response_text);
				}
			}
		}
	},
	
	// Shipping-Form functions ........................................................
	setValidShippingForm: function() {
	
		this.tbody = document.getElementById('shippingTable').childNodes[3];
		this.shippingFormInputElementsList = this.tbody.getElementsByTagName('input');
		for(var i = 0; i < this.shippingFormInputElementsList.length; i++) {
			if (i != 0) {
					this.shippingFormInputElementsList[i].setAttribute('disabled', 'true');
			} else {
				if (i == 0) {
					this.shippingFormInputElementsList[i].setAttribute('checked', 'true');
				}
			}
		}
	},
	
	sendCartToDIBS: function()  {
	
		this.shippingFormCheckAllFields();
		this.addShippingFormFields();
		this.cartItemsToSend = JSON.parse(localStorage.Items);
		
		for (var i = 0; i < this.cartItemsToSend.length; i++) {
			var inputElement = document.createElement('input');
			inputElement.setAttribute('type', 'hidden');
			inputElement.setAttribute('name', 'oiRow' + (i + 1));
			inputElement.setAttribute('value', '1;' 
									  + this.cartItemsToSend[i].category + ";"
									  + this.cartItemsToSend[i].name + ";"
									  + (this.cartItemsToSend[i].price * 100) + ";"
									  + this.cartItemsToSend[i].id + ";"
									  + "2500");
			this.checkout_form.appendChild(inputElement);
		}
		this.updateShippingInfo();
	},
	
	shippingFormCheckAllFields: function() {
	
		var invalid = false;
		this.tbody = document.getElementById('shippingTable').childNodes[3];
		this.shippingFormInputElementsList = this.tbody.getElementsByTagName('input');
		var button = document.getElementById('sfContinueButton');
		
		for(var i = 0; i < this.shippingFormInputElementsList.length; i++) {
			if (i != 0 && i != 4) {
				if (this.shippingFormInputElementsList[i].value == "") {
					invalid = true;
					this.shippingFormNewFieldsArray[this.shippingFormInputElementsList[i].name] = this.shippingFormInputElementsList[i].value;
				} else {
					this.shippingFormNewFieldsArray[this.shippingFormInputElementsList[i].name] = this.shippingFormInputElementsList[i].value;
				}
			} else {
				if (i == 4) {
					if(this.shippingFormInputElementsList[i].value == "") {
						this.shippingFormNewFieldsArray[this.shippingFormInputElementsList[i].name] = null;
					} else {
						this.shippingFormNewFieldsArray[this.shippingFormInputElementsList[i].name] = this.shippingFormInputElementsList[i].value;
					}
				}
			}
		}
		if (invalid) {
			button.setAttribute('disabled', 'true');
		} else {
			if (button.hasAttribute('disabled')) {
				button.removeAttribute('disabled');
			}
		}
	},
	
	addShippingFormFields: function() {
	
		for (var key in this.shippingFormNewFieldsArray) {
			
			var inputElement = document.createElement('input');
			inputElement.setAttribute('type', 'hidden');
			inputElement.setAttribute('name', key);
			
			if (this.shippingFormNewFieldsArray[key] == null) {
				inputElement.setAttribute('value', " ");
			} else {
				inputElement.setAttribute('value', this.shippingFormNewFieldsArray[key]);
			}
			this.checkout_form.appendChild(inputElement);
		}
		this.setOrderID();
	},
	
	setOrderID: function() {
	
		var ititialID = this.ORDER_PREFIX + new Date().getTime();
		var orderID = ititialID + "-" + this.userID.match(/[a-zA-Z]+/);
	
		this.orderID_input.setAttribute('type', 'hidden');
		this.orderID_input.setAttribute('name', 'orderId');
		this.orderID_input.setAttribute('value', orderID);
		
		this.userID_input.setAttribute('type', 'hidden');
		this.userID_input.setAttribute('name', 's_userID');
		this.userID_input.setAttribute('value', this.userID);
		
		this.checkout_form.appendChild(this.userID_input);
		this.checkout_form.appendChild(this.orderID_input);
	},
	
	updateShippingInfo: function() {
	
		var data = "";
		for (var key in this.shippingFormNewFieldsArray) {
			if (this.shippingFormNewFieldsArray[key] != "") {
				data += key + "=" + this.shippingFormNewFieldsArray[key] + "&";
			}
		}
		data += "id=" + this.userID;
	
		this.xmlHTTPrequest.open('POST', "classes/update_user_shipping_info.php", true);
		this.xmlHTTPrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		this.xmlHTTPrequest.send(data);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				devDibs.submit_button.click(); // send the form to DIBS
			} else {
				console.log("shipping update failed: " + devDibs.xmlHTTPrequest.responseText);
			}
		}
	},
	
	// Main shop functions .........................................................
	getProductID: function(productID) {
		
		var url = "classes/product_view.php?productID=" + productID; 
		this.xmlHTTPrequest.open('GET', url, true); 
		this.xmlHTTPrequest.send(null);
		this.xmlHTTPrequest.onreadystatechange = function() {
			if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
				devDibs.main_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
			}
		}
	},
	
	// DIBS callback functions .....................................................
	displayCancelPage: function() {
	
		this.first_span.style.visibility = "hidden";
		this.second_span.style.visibility = "hidden";
		this.site.appendChild(this.panels_background_div);
		
		this.xmlHTTPrequest.open('GET', "classes/cancelpage.php", true); 
		this.xmlHTTPrequest.send(null);
		this.xmlHTTPrequest.onreadystatechange = function() {
	
		if (devDibs.xmlHTTPrequest.status == 200 && devDibs.xmlHTTPrequest.readyState == 4) {
			devDibs.panels_close_img.onclick = function() {
				devDibs.first_span.style.visibility = "visible";
				devDibs.second_span.style.visibility = "visible";
				window.top.location = "http://mserve.kajohansen.com/dibs/";
				}
				devDibs.first_panel_div.innerHTML = devDibs.xmlHTTPrequest.responseText;
			}
		}
	},

}; /* End devDibs Object */

var devDibs = new DevDibs();
devDibs.setupLoginPanels();
