<!DOCTYPE html>
<html lang="en-CA">
	<head>
	<title>911 Operator Database: Emergency responder by call centre</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
		<h1>The following call centres have contacted all registered emergency responders</h1>
		</header>
		<main>
		
			<form method="GET" action="./division.php">
				<input type="hidden" id="division" name="division">
				<div class="field-inputs">
					
					<div class="button-panel">
						<button id="confirm-button" name="division" value="division">View</button>
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
				include("../../../project_html/frontend/controller/divisionController.php");
			?>
	</body>
</html>
