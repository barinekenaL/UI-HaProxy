<?php
session_start();
//echo '<pre>'; print_r($_SERVER); echo '</pre>';
if($_SERVER["REQUEST_METHOD"]=="POST") {
	$_SESSION["user"] = $_POST["login"];
} else if(!isset($_SESSION["user"])) {
	header('location:index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bary Server</title>
</head>
<body>
	<p>Welcome <?php echo $_SESSION["user"]; ?>,</p>
	<h3>Add student</h3>
	<form action="add.php" method="POST">
		<input type="text" name="nom" autofocus require>
		<input type="submit" value="Add">
	</form>
	<a href="deco.php">Log out</a>
</body>
</html>
