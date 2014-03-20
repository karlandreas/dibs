var DevDibs = function() {

	// Constants  .................................................................
	this.ORDER_PREFIX = "O-",
	
	// HTML5 on-page elements .....................................................
	
	this.site				= document.getElementById('site'),
	this.status_span 		= document.getElementById('status_span'),
	this.price_span 		= document.getElementById('price_span'),
	this.edit_basket_div	= document.getElementById('edit_basket_div'),
	this.mini_cart_div 		= document.getElementById('mini_cart_div'),
	this.edit_basket_button = document.getElementById("edit_basket_button"),  
	this.checkout_button 	= document.getElementById("checkout_button"),  
	this.main_div 			= document.getElementById("main_div"),
	this.submit_button 		= document.getElementById("submit_button"),
	this.checkout_form 		= document.getElementById("checkout_form"),
	this.back_link			= document.getElementById('back_link'),
	this.login_info_div 	= document.getElementById("login_info_div"),

	
	// HTML5 new-dom elements .....................................................
	/* Login Panels */
	this.panels_background_div 	= document.createElement('div'),
	this.panels_container_div 	= document.createElement('div'),
	this.panels_menu_div 		= document.createElement('div'),
	this.panels_slider_div 		= document.createElement('div'),
	this.panels_close_img 		= document.createElement('img'),
	// --
	this.first_panel_div 		= document.createElement('div'),
	this.second_panel_div 		= document.createElement('div'),
	// --
	this.first_span 			= document.createElement('span'),
	this.second_span 			= document.createElement("span"),
	// --
	this.login_result_div 		= document.createElement('div'),
	this.login_result_button	= document.createElement('button'),
	// --
	this.register_result_div 	= document.createElement('div'),
	this.verified_img 			= document.createElement("img"),
	/* Login Info */
	this.logged_in_span 		= document.createElement('span'),
	this.logout_button 			= document.createElement('button'),
	/* Order Info */
	this.orderID_input	 		= document.createElement('input'),
	this.userID_input		 	= document.createElement('input'),
	
	// XMLHttpRequest element ......................................................
	
	this.xmlHTTPrequest			= (window.XMLHttpRequest) ? new XMLHttpRequest() : ActiveXObject("Microsoft.XMLHTTP"),
	
	// shop.js variables .........................................................
	this.userIsLoggedIn 		= false,
	this.userID 				= null,
	this.miniShopIsEditing 		= false,
	this.checkoutFormFields 	= new Array(),
	this.cartItemsToSend		= new Array(),
	
	// form_functions_billing.js variables .......................................
	this.billingFormActiveFieldValue 		= "",
	this.billingFormActiveFieldNewValue 	= "",
	this.billingFormNewFieldsArray			= new Array(),
	this.billingFormInputElementsList 		= null,
	this.billingFormHasChanged 				= false,
	
	// form_functions_shipping.js variables ......................................
	this.shippingFormActiveFieldValue 		= "",
	this.shippingFormNewActiveFieldValue	= "",
	this.shippingFormNewFieldsArray 		= new Array(),
	this.shippingFormOriginalFieldsArray	= new Array(), //form fields from billing form 
	this.shippingFormInputElementsList 		= null, 
	this.shippingFormIsFirstLoad 			= true, 
	
	// billing and shipping form shared variables
	this.tbody 						= null, // variable for storing the the forms body element
	
	// form_functions_login.js variables
	this.validLoginFieldArray 		= [false, false], // true true when valid
	
	// form_functions_register.js variables
	this.validRegisterFieldArray 	= [false, false, false, false, false, false],
	this.tmpPassword 				= "",
	this.confirmedImages			= null,
	this.unconfirmedImages			= null,
	this.evaluatingImages			= null,
	this.registerFormlabels			= null,
	
	// form_functions_login.js variables .........................................
	this.confirmed					= true;
	
};


