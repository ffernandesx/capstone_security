<!-- Shows a list of users and their emails. -->
<?php
include('config.php');
?>

<html>
	<head>
		<title>List of users</title>
	</head>
	<body>
		<div class="content">
This is the list of members:
			<table>
				<tr>
					<th>Id</th>
					<th>Username</th>
				</tr>

<?php
//We get the IDs, usernames and emails of users
$req = mysqli_query($link, 'select id, username, email from users');
while ($dnn = mysqli_fetch_array($req)) {
?>

				<tr>
					<td class="left"><?php echo $dnn['id']; ?></td>
					<td class="left"><a href="profile.php?id=<?php echo $dnn['id']; ?>"><?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				</tr>

<?php
}
?>
			</table>
		</div>
		<div class="foot"><a href="<?php echo $url_home; ?>">Go Home</a></div>
	</body>
</html>
