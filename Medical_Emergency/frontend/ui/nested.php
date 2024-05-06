<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Patients with longer than average stays</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Number of patients that stayed in each hospital for a larger than average time</h1>
		</header>
		<main>
		<form method="GET" action="./nested.php">
			<input type="hidden" id="nestedRequest" name="nestedRequest">
			<button type="submit" id="confirm-button" name="nested" value="nest">Show</button>
		</form>
		<div class="button-panel">
				<button id="cancel-button">Back</button>
			</div>
			<script src="/home/j/jettsl/cs304/Medical_Emergency_project/project_name_here/frontend/controller/cancelButton.js"></script>
			<?php 
			include("../project_name_here/backend.php"); 		// CHANGE PATH TO YOUR DIRECTORIES
			include("../project_name_here/controller/insertController.php");				
			?>
			
	</body>
</html>