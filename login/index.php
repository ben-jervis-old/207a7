<?php
	/**
	 * Created by PhpStorm.
	 * User: ben
	 * Date: 12/10/16
	 * Time: 1:03 AM
	 */
	echo "Started";
	mysqli_report(MYSQLI_REPORT_ALL);
	if(!empty($_POST)) {
		echo "Posted";
		$servername = "localhost";
		$username = "webAccess";
		$pword = "webpassword";
		$dbName = "libraryDB";
		
		$conn = new mysqli($servername, $username, $pword, $dbName);
		
		if($conn->connect_error) {
			die("Database Connection failed: " . $conn->connect_error);
		}
		
		$prepStmtLogin = $conn->prepare("SELECT COUNT(*) FROM login WHERE email=? AND password=?");
		$email = $_POST["email"];
		$hashedPassword = hash('sha256', $_POST["password"]);
		$prepStmtLogin->bind_param("ss", $email, $hashedPassword);
		echo "Statement Bound";
		if($loginRow = $prepStmtLogin->execute()) {
			$prepStmtLogin->store_result();
			$prepStmtLogin->bind_result($count);
			if(!$prepStmtLogin->fetch() ||  $count == 0) {
				echo "<br>Count: ".$count;
				die("Login failed");
			}
			$prepStmtLogin->free_result();
			$prepStmtLogin->close();
		}
		else {
			die("Execute failed: " . $prepStmtLogin->errno . " - " . $prepStmtLogin->error);
		}
		
		if($count > 0) {
			echo "Else Started";
			if(!$prepStmtCookie = $conn->prepare("SELECT id from users where email=?")) {
				echo "Prep failed: " . $prepStmtCookie->error_list;
			}
			if(!$prepStmtCookie->bind_param("s", $email)) {
				echo "Bind failed: " . $prepStmtCookie->error_list;
			}
			if(!$userID = $prepStmtCookie->execute()) {
				echo "Execute failed: ";
			}
			echo "Cookie Set";
			setcookie("userID", $userID, time() + (86400*30), "/");
			echo "Before header";
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
