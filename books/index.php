<?php
	
	// If user attempts to navigate directly here, redirect to login
	if(!isset($_COOKIE["userID"])) {
		header("Location: /login");
	}
	
	include "../includes/databaseVariables.php";
	
	$conn = new mysqli($servername, $username, $pword, $dbName);
	
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	if(!empty($_GET["subject"])) {
		$greetString = "View books related to " . $_GET["subject"];
	}
	else {
		$greetString = "Select a topic from the menu above, or from the dropdown ";
		$greetString = $greetString . "<select name=\"topics\" id=\"topics\">";
		$greetString = $greetString . "<option disabled selected>Select topic</option>";
		$greetString = $greetString . "<option>Engineering</option>";
		$greetString = $greetString . "<option>Medicine</option>";
		$greetString = $greetString . "<option>Development</option>";
		$greetString = $greetString . "</select><button id=\"go-button\">Go</button>";
	}
?>

<html>
	<head>
		<title>Assigment 7 | Task 1</title>

		<?php include "../includes/headerIncludes.html"; ?>
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
					<td class="text-center">Check Out</td>
					
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
								echo "<td class='text-center'><a href='/checkout/?bookID=$bookID'><span class='glyphicon glyphicon-share'></span></a></td>";
								echo "</tr>";
							}
							
							$prepStmtSearch->free_result();
							$prepStmtSearch->close();
						}
						else {
							echo "<tr>";
							echo "<td colspan=\"4\">No results found</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</div>
		<script>
			$( function () {
				$("#topics").selectmenu();
				$("#go-button").button();
				$("#go-button").click(function (event) {
					event.preventDefault();
//					console.log("/books/?subject=" + $("#topics").val());
					window.location.replace("/books/?subject=" + $("#topics").val());
				})
			});
		</script>
	</body>
</html>