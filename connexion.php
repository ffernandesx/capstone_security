<!-- Authenticate a registered user. -->
<?php
include('config.php');
?>

<html>
    <head>
        <title>Connexion</title>
    </head>
    <body>
<?php
//If the user is logged, log him out
if(isset($_SESSION['username']))
{
	//Log he out by deleting the username and userid sessions
	unset($_SESSION['username'], $_SESSION['userid']);
?>
<div class="message">You have successfuly been loged out.<br />
<a href="<?php echo $url_home; ?>">Home</a></div>
<?php
}
else
{
	$ousername = '';
	//Check if the form has been sent
	if(isset($_POST['username'], $_POST['password']))
	{
		//Remove slashes depending on the configuration
		if(get_magic_quotes_gpc())
		{
			$ousername = stripslashes($_POST['username']);
			$username  = mysqli_real_escape_string($link, stripslashes($_POST['username']));
			$password  = stripslashes($_POST['password']);
		}
		else
		{
			$username = mysqli_real_escape_string($link, $_POST['username']);
			$password = $_POST['password'];
		}
		//Get the password of the user
		$req = mysqli_query($link, 'select password,id,salt from users where username="'.$username.'"');
		$dn  = mysqli_fetch_array($req);
		$password = hash("sha512", $dn['salt'].$password); // Hash with the salt to match database.
		//Compare the submited password and the real one, and we check if the user exists
		if ($dn['password'] == $password and mysqli_num_rows($req)>0) {
			//If the password is good, we dont show the form
			$form = false;
			//Save the user name in the session username and the user Id in the session userid
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['userid'] = $dn['id'];
?>
<div class="message">You have successfuly been logged. You can access to your member area.<br />
<a href="<?php echo $url_home; ?>">Home</a></div>
<?php
		}
		else {
			//Otherwise, say the password is incorrect.
			$form    = true;
			$message = 'The username or password is incorrect.';
		}
	}
	else $form = true;
	if($form) {
		//Display a message if necessary
		if(isset($message)) echo '<div class="message">'.$message.'</div>';

	//Display the form
?>
<div class="content">
    <form action="connexion.php" method="post">
        Please type your IDs to log in:<br />
        <div class="center">
            <label for="username">Username</label><input type="text" name="username" id="username" value="<?php echo htmlentities($ousername, ENT_QUOTES, 'UTF-8'); ?>" /><br />
            <label for="password">Password</label><input type="password" name="password" id="password" /><br />
            <input type="submit" value="Log in" />
		</div>
    </form>
</div>
<?php
	}
}
?>
		<div class="foot"><a href="<?php echo $url_home; ?>">Go Home</a></div>
	</body>
</html>
