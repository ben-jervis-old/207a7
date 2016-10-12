<?php
	/**
	 * Created by PhpStorm.
	 * User: ben
	 * Date: 12/10/16
	 * Time: 4:58 PM
	 */
	
	// If user is not logged in, redirect
	if(!isset($_COOKIE["userID"])) {
		header("Location: /login");
	}
	
	// If user has not selected a book, redirect
	if(empty($_GET)) {
		header("Location: /books");
	}
	
	// Establish DB connection
	include "../includes/databaseVariables.php";
	
	$conn = new mysqli($servername, $username, $pword, $dbName);
	
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$buttonAttr = "";
	
	if($_GET["confirmed"] == "true") {
		$prepStmtCheckout = $conn->prepare("INSERT INTO borrowings (userID, bookID, date, time) VALUES (?, ?, CURRENT_DATE(), CURRENT_TIME())");
		$prepStmtCheckout->bind_param("ii", $_COOKIE["userID"], $_GET["bookID"]);
		if($prepStmtCheckout->execute()) {
			$successMessage = "<div class='alert alert-success'><strong>Success. </strong> Checked Out Successfully</div>";
			$buttonAttr = "disabled=\"disabled\"";
		}
		else {
			$successMessage = "<div class='alert alert-danger'><strong>Error. </strong>An error occurred during checkout process</div>";
		}
	}
	else {
		$prepStmtUser = $conn->prepare("SELECT firstName, lastName, email FROM users WHERE id=?");
		$prepStmtUser->bind_param("i", $_COOKIE["userID"]);
		if($prepStmtUser->execute()) {
			$prepStmtUser->store_result();
			$prepStmtUser->bind_result($first, $last, $email);
			if(!$prepStmtUser->fetch()) {
				die("Database retrieval error: User Details");
			}
			
			$prepStmtUser->free_result();
			$prepStmtUser->close();
		}
		
		$prepStmtBook = $conn->prepare("SELECT title, author, callNum, barcode FROM books where id=?");
		$prepStmtBook->bind_param("i", $_GET["bookID"]);
		if($prepStmtBook->execute()) {
			$prepStmtBook->store_result();
			$prepStmtBook->bind_result($title, $author, $callNum, $barcode);
			if(!$prepStmtBook->fetch()) {
				die("Database retrievel error: Book Details");
			}
			
			$prepStmtBook->free_result();
			$prepStmtBook->close();
		}
	}
	
	$confirmButtonRef = "/checkout/?bookID=".$_GET["bookID"]."&confirmed=true";
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
			<h1>ISIT207 Library - Checkout Book</h1>
			<div class="panel panel-default">
				<div class="panel-heading">User Details</div>
				<table class="table">
					<thead>
						<td><strong>Name</strong></td>
						<td><strong>Email Address</strong></td>
					</thead>
					<tr>
						<?php
							echo "<td>$first $last</td>";
							echo "<td>$email</td>";
						?>
					</tr>
				</table>
			</div> <!-- panel close -->
			<div class="panel panel-default">
				<div class="panel-heading">Book Details</div>
				<table class="table">
					<thead>
						<td><strong>Title</strong></td>
						<td><strong>Author</strong></td>
						<td><strong>Call Number</strong></td>
						<td><strong>Barcode</strong></td>
					</thead>
					<tr>
						<?php
							echo "<td>$title</td>";
							echo "<td>$author</td>";
							echo "<td>$callNum</td>";
							echo "<td>$barcode</td>";
						?>
					</tr>
				</table>
			</div> <!-- panel close -->
			<a class="btn btn-primary" <?php echo $buttonAttr; ?> href="<?php echo $confirmButtonRef; ?>">Confirm Checkout</a>
			<?php echo $successMessage; ?>
		</div> <!-- container close -->
	</body>
</html>
