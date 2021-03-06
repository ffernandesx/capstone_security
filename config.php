<?php
//Start sessions
session_start();

//Access to Heroku DataBase. Account data hardcoded.
$heroku_svr = 'eu-cdbr-west-03.cleardb.net:3306'; // Server's URL
$heroku_usr = 'b57e786cb981f4';						// Root user.
$heroku_pwd = '64508dca';							  // Password.
$heroku_sch = 'heroku_a8603b0c93de4b6';				// Schema.
$link	    = new mysqli($heroku_svr, $heroku_usr, $heroku_pwd, $heroku_sch);

if (!$link) {
	die('Could not connect: ' . mysqli_error());
}

//Webmaster Email
$mail_webmaster = 'fernandesx@hotmail.com';

//Top site root URL
$url_root = 'https://fernandesx.herokuapp.com/';

//Home page file name
$url_home = 'index.php';

//Design Name
$design = 'default';

// checkPassword: Check password strength. Returns true if it is Ok.
// $pwd receives the password to test.
// $errors returns the non-compliant items of the provided password

function checkPassword($pwd, &$errors) {
	$errors_init = $errors;

	if (strlen($pwd) < 8) $errors[] = "Password must have at least 8 characters!";
	if (!preg_match("#[0-9]+#", $pwd)) $errors[] = "Password must include at least one number!";
	if (!preg_match("#[a-zA-Z]+#", $pwd)) $errors[] = "Password must include at least one letter!";
	if (!preg_match("#[a-z]+#", $pwd)) $errors[] .= "Password must include at least one lowercase letter!";
	if (!preg_match("#[A-Z]+#", $pwd)) $errors[] .= "Password must include at least one uppercase letter!";
	if (!preg_match("#\W+#", $pwd)) $errors[] .= "Password must include at least one symbol!";

	return ($errors == $errors_init);
}

// getKey: Set and retrieve password for message database encryption.
// $user1 and $user2: users communicating each other.

function getKey($user1, $user2) {
	global $link;

	//Message DataBase. Access data cryptography hardcoded.
	$cipher = "aes-128-cbc";
	$ivlen  = openssl_cipher_iv_length($cipher);
	$iv		= base64_decode("5AIQsI+LyPUfWJpTo5em6A=="); // A hardcoded random iv  of 128 bits.
	$dbkey  = base64_decode("zT/PCCuJnUGvSUYtd95tSw=="); // A hardcoded random key of 128 bits.

	if ($user1 > $user2) {// Just to make $user1 < $user2. Swap if necessary. 
		$tmp = $user1;
		$user1 = $user2;
		$user2 = $tmp;
	};

	$method = openssl_get_cipher_methods();
	if (in_array($cipher, $method)) {
		$key = base64_encode(openssl_random_pseudo_bytes(24)); // A random key of 192 bits to be used in case of being the first message.
		$encrypted_key = openssl_encrypt($key, $cipher, $dbkey, 0, $iv);

		$req = mysqli_query($link, 'select * from messagekeys where user1="'.$user1.'" and user2="'.$user2.'"');
		$dn  = mysqli_num_rows($req);
		$dat = mysqli_fetch_array($req);

		// No key. First message. Create a new key.
		if ($dn == 0) mysqli_query($link, 'insert into messagekeys(user1, user2, mskey) values ('.$user1.', "'.$user2.'", "'.$encrypted_key.'")');
		else {
			$cryp_key = $dat['mskey'];
			$key = openssl_decrypt($cryp_key, $cipher, $dbkey, 0, $iv);
		}
		return $key;
	}
	else return false;
}
?>
