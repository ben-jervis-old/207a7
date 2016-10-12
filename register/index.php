<?php
	/**
	 * Created by PhpStorm.
	 * User: ben
	 * Date: 12/10/16
	 * Time: 1:03 AM
	 */
	
	if(!empty($_POST)) {
		
		include "../includes/databaseVariables.php";
		
		$conn = new mysqli($servername, $username, $pword, $dbName);
		
		if($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$prepStmtUsers = $conn->prepare("INSERT INTO users (firstName, lastName, email, joinDate) VALUES (?, ?, ?, CURRENT_DATE())");
		$prepStmtUsers->bind_param("sss", $_POST["firstName"], $_POST["lastName"], $_POST["email"]);
		if(!$prepStmtUsers->execute()) {
			echo "Execute failed: " . $prepStmtUsers->errno . " - " . $prepStmtUsers->error;
		}
		else {
			$lastID = $conn->insert_id;
			
			$prepStmtLogin = $conn->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
			$prepStmtLogin->bind_param("ss", $_POST["email"], hash('sha256', $_POST["password"]));
			if(!$prepStmtLogin->execute()) {
				echo "Execute failed: " . $prepStmtLogin->errno . " - " . $prepStmtLogin->error;
			}
			else {
				header("Location: /users");
			}
		}
	}
?>

<html>
	<head>
		<title>Assignment Library | Register</title>
		
		<!--Bootstrap Link-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="../main.css">
	</head>
	<body>
		<?php include '../includes/navbar.html'; ?>
		<h1 class="text-center">Register a new account</h1>
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading">Enter Your Details</div>
				<form action="index.php" method="post">
					<div class="form-group">
						<input type="text" class="form-control center-block" id="firstName" name="firstName" placeholder="First Name" required>
						<input type="text" class="form-control center-block" id="lastName" name="lastName" placeholder="Last Name" required>
						<input type="email" class="form-control center-block" id="email" name="email" placeholder="Email" required>
						<input type="password" class="form-control center-block" id="password" name="password" placeholder="Password" required>
						<input type="password" class="form-control center-block" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
					</div>
					<button type="submit" class="btn btn-default center-block">Register</button>
				</form>
			</div>
		</div>
	</body>
</html>
