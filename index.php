<!-- Home page. App starts here. -->
<?php
include('config.php');
?>

<html>
<head>
</head>
<body>
	Teste
<?php

?>

Hello
<?php
if(isset($_SESSION['username'])) {
	echo ' '.htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8');}
?>

<br />Welcome<br />
<?php
//If the user is logged, display links to edit his infos, to see his pms and to log out
if (isset($_SESSION['username'])) {
	echo 'You can <a href="users.php">see the list of users</a>.<br /><br />';

	//Count the number of new messages the user has
	$nb_new_pm = mysqli_fetch_array(mysqli_query($link, 'select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
	//The number of new messages is in the variable $nb_new_pm
	$nb_new_pm = $nb_new_pm['nb_new_pm'];
	//Display the links
?>

<a href="edit_infos.php">Edit my personnal information</a><br />
<a href="list_pm.php">My personnal messages (<?php echo $nb_new_pm; ?> unread)</a><br />
<a href="connexion.php">Logout</a>
<?php
}
else {
//Otherwise, display a link to log in and to Sign up
?>
<a href="sign_up.php">Sign up</a><br />
<a href="connexion.php">Log in</a>
<?php
}
?>
		</div>
	</body>
</html>
