<?php
// facebook
require('../libraries/facebook/facebook.php');
$facebook = new Facebook(array( 'appId' => FB_KEY,  'secret' => FB_SECRET ));
setcookie('fbs_' . $facebook->getAppId(),'',time()-3600,'/','kajohansen.com');
$facebook->destroySession();
session_destroy();

// google
unset($_SESSION['token']);
// all users
unset($_SESSION['g_user']);
unset($_SESSION['l_user']);
unset($_SESSION['f_user']);
unset($_SESSION['d_user']);

header('Location: http://mserve.kajohansen.com/dibs');

?>