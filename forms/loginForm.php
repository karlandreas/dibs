<?php
	require_once('../includes/constants.php');
	require_once('../libraries/facebook/facebook.php');
	require_once '../libraries/google/Google_Client.php';
	// Facebook
	$facebook = new Facebook(array( 'appId' => FB_KEY,  'secret' => FB_SECRET ));
	$fb_params = array(
		'scope' => 'email',
		'redirect_uri' => 'http://mserve.kajohansen.com/dibs/oauthcallback/'
	);
	// Google
	$google_client = new Google_Client();
	$google_client->setApplicationName("KA Access OAuth");
	$google_client->setClientId(G_KEY);
	$google_client->setClientSecret(G_SECRET);
	$google_client->setState("google");
	$google_client->setRedirectUri('http://mserve.kajohansen.com/dibs/oauthcallback/google.php');
	$google_client->setScopes(array("https://www.googleapis.com/auth/userinfo.profile", "https://www.googleapis.com/auth/userinfo.email"));
	// Linkedin
	$linkedin_params = array('response_type' => 'code', 'client_id' => L_KEY, 'scope' => L_SCOPE, 'state' => 'linkedin', 'redirect_uri' => L_REDIRECT_URI, );
	$linkedin_link = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($linkedin_params);
?>
<!-- User Login Form -->
<div class="form_encap_div">
<form id="login_form" style="margin-left:50px;">
	<table>
	<thead>
	<th colspan="2">
		<p style="text-align:center;">Login with an existing Delicion account</p>
	</th>
	</thead>
	<tbody>
	<tr>
	<td>
		<label>Username :</label></td>
	<td>
		<input type="text" id="login_username_field" 
						   onfocus="devDibs.loginEnter();" 
						   onblur="devDibs.checkUsername()"
						   onkeyup="devDibs.checkUsername()" 
						   name="username" 
						   size="30"
						   class="largeInput" /></td>
	</tr>
	<tr>
	<td>
		<label>Password :</label></td>
	<td>
		<input type="password" onfocus="devDibs.loginEnter()" 
							   onblur="devDibs.checkPassword()"
							   onkeyup="devDibs.checkPassword()" 
							   name="password" 
							   size="30"
							   class="largeInput" /></td>
	</tr>
	</tbody>
	<tfoot>
		<tr class="form_tr">
			<td>&nbsp;</td>
			<td>
				<input type="button" id="login_user_button" 
					   				 value="Login"
					   				 disabled="true"
					   				 onblur="devDibs.preventDef()"
					   				 onclick="devDibs.sendLogin()" />
			</td>
		</tr>
	</tfoot>
	</table>
</form>
<div style="padding:10px;">
<b>Or Login with your favorite social account</b><br /><br />
<a href="<?php echo($facebook->getLoginUrl($fb_params)); ?>"><img style="height:27px;" src="images/login_buttons/f_loginButton2.png" align="Login Button" /></a>
<a href="<?php echo($google_client->createAuthUrl()); ?>"><img style="height:27px;" src="images/login_buttons/g_loginButton2.png" align="Login Button" /></a>
<a href="<?php echo($linkedin_link) ?>"><img style="height:27px;" src="images/login_buttons/l_loginButton2.png" align="Login Button" /></a>
</div>
</div>