<!-- Register a new user. -->
<?php
include('config.php');
?>

<html>
	<head>
		<title>Sign up</title>
	</head>
	<body>
<?php
//Check if the form has been sent
if(isset($_POST['username'], $_POST['password'], $_POST['passverif']) and $_POST['username'] != '')
{
	//Remove slashes depending on the configuration
	if(get_magic_quotes_gpc())
	{
		$_POST['username']  = stripslashes($_POST['username']);
		$_POST['password']  = stripslashes($_POST['password']);
		$_POST['passverif'] = stripslashes($_POST['passverif']);
//		$_POST['email']  	= stripslashes($_POST['email']);
//		$_POST['avatar']	= stripslashes($_POST['avatar']);
	}
	//Check if the two passwords are identical
	$errors = [];
	if($_POST['password'] == $_POST['passverif'])
	{
		//Check if the choosen password is strong enough.
		if(checkPassword($_POST['password'], $errors))
		{
			//Check if the email form is valid
//			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
			if(1)
			{
				//Protect the variables
				$username = mysqli_real_escape_string($link, $_POST['username']);
				$password = mysqli_real_escape_string($link, $_POST['password']);
//				$email	  = mysqli_real_escape_string($link, $_POST['email']);
//				$avatar   = mysqli_real_escape_string($link, $_POST['avatar']);
				$salt	  = (string)rand(10000, 99999);	     //Generate a five digit salt.
				$password = hash("sha512", $salt.$password); //Compute the hash of salt concatenated to password.
				//Check if there is no other user using the same username
				$dn = mysqli_num_rows(mysqli_query($link, 'select id from users where username="'.$username.'"'));
				if($dn == 0)
				{
					//Count the number of users to give an ID to this one
					$dn2 = mysqli_num_rows(mysqli_query($link, 'select id from users'));
					$id = $dn2 + 1;
					//Save the informations to the databse
					if(mysqli_query($link, 'insert into users(id, username, password, signup_date, salt) values ('.$id.', "'.$username.'", "'.$password.'", "'.time().'","'.$salt.'")'))
					{
						//Sont display the form
						$form = false;
?>
		<div class="message">You have successfuly been signed up. You can log in.<br />
		<a href="connexion.php">Log in</a></div>
<?php
					}
					else
					{
						//Otherwise, we say that an error occured
						$form	= true;
						$message = 'An error occurred while signing up.';
					}
				}
				else
				{
					//Otherwise, we say the username is not available
					$form	= true;
					$message = 'The username you want to use is not available, please choose another one.';
				}
			}
			else
			{
				//Otherwise, we say the email is not valid
				$form	= true;
				$message = 'The email you entered is not valid.';
			}
		}
		else
		{
			//Otherwise, we say the password is too weak
			$form	= true;
			$message = '';
			foreach ($errors as $item)
				$message = $message.$item."<BR>";
		}
	}
	else
	{
		//Otherwise, we say the passwords are not identical
		$form	 = true;
		$message = 'The passwords you entered are not identical.';
	}
}
else
{
	$form = true;
}
if ($form) {
	//We display a message if necessary
	if(isset($message)) echo '<div class="message">'.$message.'</div>';

	//We display the form again
?>
		<div class="content">
			<form action="sign_up.php" method="post">
				Please fill the following form to sign up:<br />
				<div class="center">
					<label for="username">Username</label><input type="text" name="username" value="<?php if(isset($_POST['username'])){echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
					<label for="password">Password<span class="small">(8 characters min.)</span></label><input type="password" name="password" /><br />
					<label for="passverif">Password<span class="small">(verification)</span></label><input type="password" name="passverif" /><br />
					<input type="submit" value="Sign up" />
				</div>
			</form>
		</div>
<?php
}
?>
		<div class="foot"><a href="<?php echo $url_home; ?>">Go Home</a></div>
	</body>
</html>
