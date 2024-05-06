<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Join Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Join and view emergency addresses by hospital</h1>
		</header>
		<main>
			<form method="GET" action="./join.php">
				<input type="hidden" id="joinRequest" name="joinRequest">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="hospital-name-input">Hospital name</label>
						<input type="text" name="hospital-name-input" id="hospital-name-input"></select>
					</div>
					<div class="button-panel">
						<button type="submit" id="confirm-button" name="join" value="join">Apply and view</button>
					</div>
				</div>
			</form>
			<?php 
			include("../project_name_here/backend.php"); 		// CHANGE PATH TO YOUR DIRECTORIES
			include("../project_name_here/controller/insertController.php");							
			?>
			<div class="button-panel">
				<button id="cancel-button">Cancel</button>
			</div>
			<script src="../controller/cancelButton.js"></script>
	</body>
</html>