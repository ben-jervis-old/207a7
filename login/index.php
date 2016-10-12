<?php
	/**
	 * Created by PhpStorm.
	 * User: ben
	 * Date: 12/10/16
	 * Time: 1:03 AM
	 */
	
	if(isset($_COOKIE["userID"])) {
		header("Location: /books");
	}
	$loginFailed = false;
	
	// If there is POST data
	if(!empty($_POST)) {
		
		$loginSuccess = false;
				
		// Establish a connection to the DB
		$servername = "localhost";
		$username = "webAccess";
		$pword = "webpassword";
		$dbName = "libraryDB";
		
		$conn = new mysqli($servername, $username, $pword, $dbName);
		
		if($conn->connect_error) {
			die("Database Connection failed: " . $conn->connect_error);
		}
		
		// Use a prepared statement to determine if credentials exist in the DB
		$prepStmtLogin = $conn->prepare("SELECT COUNT(*) FROM login WHERE email=? AND password=?");
		
		// Bind the POST data to the prepared statement
		$prepStmtLogin->bind_param("ss", $_POST["email"], hash('sha256', $_POST["password"]));
		
		if($prepStmtLogin->execute()) {
			
			// This is required to prevent the "Commands out of order" error
			$prepStmtLogin->store_result();
			$prepStmtLogin->bind_result($count);
			
			// If the count is 0, the details are not matched
			if($prepStmtLogin->fetch() &&  $count > 0) {
				$loginSuccess = true;
			}
			else {
				$loginFailed = true;
			}
			
			// Clear and close the prepared statement
			$prepStmtLogin->free_result();
			$prepStmtLogin->close();
		}
		else {
			die("Execute failed: " . $prepStmtLogin->errno . " - " . $prepStmtLogin->error);
		}
		
		if($loginSuccess) {
			
			// Retrieve the User ID from the DB
			$prepStmtCookie = $conn->prepare("SELECT id from users where email=?");
			$prepStmtCookie->bind_param("s", $email);
				
			$userID = $prepStmtCookie->execute();
				
			// Set a cookie to store the user ID, valid for 30 days
			setcookie("userID", $userID, time() + (86400*30), "/");
			
			// Since login was successful, redirect to the catalogue page
			header("Location: /books");
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
			<div class="panel panel-default center-block login-panel">
				<div class="panel-heading">Login to Library System</div>
				<div class="form-wrapper">
					<form action="index.php" method="post">
						<div class="form-group center-block">
							<label for="emailInput">Email Address</label>
							<input type="email" class="form-control" id="emailInput" name = "email" placeholder="Email">
						</div>
						<div class="form-group center-block">
							<label for="pwordInput">Password</label>
							<input type="password" class="form-control" id="pwordInput" name="password" placeholder="Password">
						</div>
						<?php
							if($loginFailed) {
								echo "<div class=\"alert alert-danger\"><strong>Invalid Login </strong>We don't recognise that combination.</div>";
							}
						?>
						<button type="submit" class="btn btn-default btn-lg center-block">Login</button>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>

