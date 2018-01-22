<?
if (isset ($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 'logout') {
		session_start();
		unset($_SESSION['UID']);
	}
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Login page</title>
	<link rel="stylesheet" href="styles/custom_styles.css">
</head>
<body>
<div id="container">
	<form action="index.php" method="post">
		<label for="name"><b>User:</b></label>
		<input type="name" name="name" id="name" value="">
		<label for="password"><b>Password:</b></label>
		<input type="password" name="password" id="password" value="1111">
		<div id="lower">
			<input type="submit" value="Enter">
		</div>
	</form>
</div>
</body>
</html>
	
	
	
	
	
		
	