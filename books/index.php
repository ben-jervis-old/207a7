<?php
	
	// If user attempts to navigate directly here, redirect to login
	if(!isset($_COOKIE["userID"])) {
		header("Location: /login");
	}
	
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
					<td>Title</td>
					<td>Author</td>
					<td>Call Number</td>
					<td>Check Out</td>
					
				</thead>
				<?php
					if(!empty($_GET["subject"])) {
						
						if(!$prepStmtSearch = $conn->prepare("SELECT id, title, author, callNum FROM books where topics LIKE ?")) {
							echo "Prep Statement Failed";
						}
						$searchString = "%" . strtolower($_GET["subject"]) . "%";
						if(!$prepStmtSearch->bind_param("s", $searchString)) {
							echo "Bind failed";
						}
						
						if($prepStmtSearch->execute() && $prepStmtSearch->store_result() && $prepStmtSearch->num_rows() > 0) {
							
							$prepStmtSearch->bind_result($bookID, $title, $author, $callNum);
							
							while($prepStmtSearch->fetch()) {
								echo "<tr>";
								echo "  <td>$title</td>";
								echo "  <td>$author</td>";
								echo "  <td>$callNum</td>";
								echo "<td><a href='/checkout/?bookID=$bookID'><span class='glyphicon glyphicon-share'></span></a></td>";
								echo "</tr>";
							}
							
							$prepStmtSearch->free_result();
							$prepStmtSearch->close();
						}
						else {
							echo "<tr>";
							echo "<td colspan=\"3\">No results found</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</div>
	</body>
</html>