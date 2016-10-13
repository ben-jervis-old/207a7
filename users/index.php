<?php
	
	// If user attempts to navigate directly here, redirect to login
	if(!isset($_COOKIE["userID"])) {
		header("Location: /login");
	}
	
	// Establish DB connection
	include "../includes/databaseVariables.php";
	
	$conn = new mysqli($servername, $username, $pword, $dbName);
	
	if($conn->connect_error) {
		die("Databse Connection failed: " . $conn->connect_error);
	}
?>

<html>
	<head>
		<title>Assigment 7 | Task 1</title>
		
		<?php include "../includes/headerIncludes.html"; ?>
	</head>
	<body>
		<div id="dialog" title="Access Restriction">
			<p>Only Administrators should access this page</p>
		</div>
		<div class="container">
			<?php include '../includes/navbar.html'; ?>
			<h1>ISIT207 Library - User List</h1>
			<h4>View the list of users</h4>
			<div class="panel panel-default">
				<div class="panel-heading">Membership List</div>
				<table class="table">
					<thead class="">
						<td>User ID</td>
						<td>Name</td>
						<td>Email Address</td>
					
					</thead>
					<?php
						$sqlString = "SELECT id, firstName, lastName, email FROM users";
						$result = $conn->query($sqlString);
						
						if($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								$idStr = $row["id"];
								$nameStr = implode(" ", [$row["firstName"], $row["lastName"]]);
								$emailStr = $row["email"];
								echo "<tr><td>$idStr</td><td>$nameStr</td><td>$emailStr</td></tr>";
							}
						}
					
					?>
				</table>
			</div> <!-- panel close -->
		</div> <!-- container close -->
	</body>
	<script>
		$(  function () {
			$("#dialog").dialog();
		});
	</script>
</html>