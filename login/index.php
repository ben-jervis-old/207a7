<?php
	/**
	 * Created by PhpStorm.
	 * User: ben
	 * Date: 12/10/16
	 * Time: 1:03 AM
	 */
	
	if(!empty($_POST)) {
		$servername = "localhost";
		$username = "webAccess";
		$pword = "webpassword";
		$dbName = "libraryDB";
		
		$conn = new mysqli($servername, $username, $pword, $dbName);
		
		if($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$prepStmtLogin = $conn->prepare("SELECT COUNT(*) FROM login WHERE email=? AND password=?");
		$prepStmtLogin->bind_param("ss", $_POST["email"], hash('sha256', $_POST["password"]));
		if(!$prepStmtLogin->execute()) {
			echo "Execute failed: " . $prepStmtLogin->errno . " - " . $prepStmtLogin->error;
		}
		else {
			header("Location: /users");
		}
	}
?>

<html>
	<head>
		<title>Assignment Library | Login</title>
		
		<!--Bootstrap Link-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="../main.css">
	</head>
	<body>
		<div class="container">
			<h1 class="text-center">Login</h1>
			<div class="panel panel-default">
				<div class="panel-heading">Login to Library System</div>
				<form action="index.php" method="post">
					<div class="form-group center-block">
						<label for="emailInput">Email Address</label>
						<input type="email" class="form-control" id="emailInput" name = "email" placeholder="Email">
					</div>
					<div class="form-group center-block">
						<label for="pwordInput">Password</label>
						<input type="password" class="form-control" id="pwordInput" name="password" placeholder="Password">
					</div>
					<button type="submit" class="btn btn-default center-block">Login</button>
				</form>
			</div>
		</div>
	</body>
</html>
