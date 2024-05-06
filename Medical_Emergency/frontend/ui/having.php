<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Count emergencies by hospital</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Count emergencies by hospital</h1>
		</header>
		<main>
			<label class="instruction">
				<h3>Please insert the following dates:</h3>
			</label>
			<form method="GET" action="./having.php">
				<input type="hidden" id="having" name="having">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="begin-date-input">From</label>
						<input type="text" id="begin-date-input" name="begin-date-input"></input>
					</div>
					<div class="labelled-input">
						<label for="end-date-input">To</label>
						<input type="text" id="end-date-input" name="end-date-input"></input>
					</div>
					<div class="labelled-input">
						<label for="min-emergencies-input">Minimum number of emergencies</label>
						<input type="text" id="min-emergencies-input" name="min-emergencies-input"></input>
					</div>
					<div class="button-panel">
						<button id="confirm-button" name="having" value="having">Search and view</button>
					</div>
				</div>
			</form>

			<div class="relation-table">
				<table>
					<tr>
						<td>Tuples will appear here</td>
					</tr>
				</table>
			</div>
			<div class="button-panel">
				<button id="cancel-button">Back</button>
			</div>
			<script src="../controller/cancelButton.js"></script>
			<?php
				include("../../../project_html/backend.php");
				include("../../../project_html/frontend/controller/insertController.php");
			?>
	</body>
</html>