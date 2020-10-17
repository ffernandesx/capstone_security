<?php
include('config.php');
?>

<html>
	<head>
		<title>Profile of an user</title>
	</head>
	<body>
		<div class="content">

<?php
//Check if the users ID is defined
if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
	//Check if the user exists
	$dn = mysqli_query($link, 'select username, email, avatar, signup_date from users where id="'.$id.'"');
	if (mysqli_num_rows($dn)>0) {
		$dnn = mysqli_fetch_array($dn);
		//Display the user datas
?>
This is the profile of "<?php echo htmlentities($dnn['username']); ?>" :
	<table style="width:500px;">
		<tr>
			<td class="left"><h1><?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
This user joined the website on <?php echo date('Y/m/d',$dnn['signup_date']); ?></td>
		</tr>
	</table>
<?php
//Add a link to send a pm to the user
		if (isset($_SESSION['username']))
?>
<br /><a href="new_pm.php?recip=<?php echo urlencode($dnn['username']); ?>" class="big">Send a PM to "<?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?>"</a>
<?php
	}
	else echo 'This user dont exists.';
}
else echo 'The user ID is not defined.';
?>
		</div>
		<div class="foot"><a href="users.php">Go to the users list</a></div>
	</body>
</html>
