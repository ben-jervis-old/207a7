<?php
	
	$servername = "localhost";
	$username = "webAccess";
	$pword = "webpassword";
	$dbName = "libraryDB";
	
	$conn = new mysqli($servername, $username, $pword, $dbName);
	
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	if(!empty($_GET["subject"])) {
		$greetString = "View books related to " . $_GET["subject"];
	}
	else {
		$greetString = "Select a topic from the menu above";
	}
?>

<html>
	<head>
		<title>Assigment 7 | Task 1</title>

		<!--Bootstrap Link-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="../main.css">
	</head>
	<body>
		<div class="container">
			<?php include '../includes/navbar.html'; ?>
			<h1>ISIT207 Library - Browse the Catalogue</h1>
			<h4><?php echo $greetString?></h4>
			<table class="table">
				<thead>
					<td>User ID</td>
					<td>Name</td>
					
				</thead>
				<tr>
					<td>First entry</td>
					<td>Second entry</td>
				</tr>
			</table>
			<button class="btn btn-lg">Button Test</button>
		</div>
	</body>
</html>