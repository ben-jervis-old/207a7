<html>
	<head>
		<title>Assigment 7 | Task 1</title>

		<!--Bootstrap Link-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<div class="container">
			<?php include 'navbar.html'; ?>
			<h1>Library Test Page</h1>
			<h4>View books related to <?php echo $_GET["subject"]?></h4>
			<table class="table">
				<thead>
					<td></td>
					<td>Second Col</td>
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