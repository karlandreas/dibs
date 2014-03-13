<?php
class User {
	private $userID;
	public $userExtID;
	private $email;
	private $locale;
	private $username;
	private $password;
	private $salt;
	private $verified;
	private $regDate;
	public $account;
	public $cover;
	public $first_name;
	public $middle_name;
	public $last_name;
	public $name;
	public $link;
	public $gender;
	public $picture;
    
	function say_hello() {
		echo "Hello from inside the class " . get_class($this) . ".<br />";
	}
	
	function authenticate_user($pass) {
		 $entered_password = sha1("Ha litt" . $this->salt . "paa" . $pass);
		 return $entered_password == $this->password;
	}
	
	function get_userExtID() {
		return $this->userExtID;
	}
		
}
?>