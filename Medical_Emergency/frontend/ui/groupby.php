<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Count hospital staff by specialty</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Count hospital staff by specialty</h1>
		</header>
		<main>
			<form method="GET" action="./groupby.php">
				<input type="hidden" id="groupbyRequest" name="groupbyRequest">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="hospital-name-input">Hospital name</label>
						<input type="text" name = "hospital-name-input" id="hospital-name-input"></select>
					</div>
					<div class="button-panel">
						<button id="confirm-button" name="groupBy" value="GroupBy">Search and view</button>
					</div>
				</div>
			</form>

			<?php 
			include("../project_name_here/backend.php"); 		// CHANGE PATH TO YOUR DIRECTORIES
			include("../project_name_here/controller/insertController.php");								
			?>

			<div class="button-panel">
				<button id="cancel-button">Back</button>
			</div>
			<script src="../controller/cancelButton.js"></script>
	
	</body>
</html>