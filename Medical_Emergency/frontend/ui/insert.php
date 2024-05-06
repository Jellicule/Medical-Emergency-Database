<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Insert Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Insert emergencies</h1>
		</header>
		<main>
			<label class="instruction">
				<h3>Please insert the following information:</h3>
			</label>
			<form method="POST" action="./insert.php">
				<input type="hidden" id="insertRequest" name="insertRequest">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="date-input">Date(YYY-MM-DD) [REQUIRED]</label>
						<input id="date-input" name="date-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="description-input">Description [REQUIRED]</label>
						<input id="description-input" name="description-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="location-input">Location [REQUIRED]</label>
						<input id="location-input" name="location-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="civilian-id-input">Civilian ID [REQUIRED]</label>
						<input id="civilian-id-input" name="civilian-id-input" type="number"></input>
					</div>
					<div class="labelled-input">
						<label for="hospital-name-input">Hospital name [REQUIRED]</label>
						<input id="hospital-name-input" name="hospital-name-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="hospital-stay-length-input">Hospital stay length</label>
						<input id="hospital-stay-length-input" name="hospital-stay-length-input" type="number"></input>
					</div>
					<div class="labelled-input">
						<label for="is-discharged-input">Have they been discharged?</label>
						<input id="is-discharged-input" name="is-discharged-input" type="checkbox" value="True"></input>
					</div>
				</div>
				<button type="submit" id="confirm-button" name="insertEmergency" value="Insert">Add emergency to database</button>
			</form>
			
			<div class="button-panel">
				<button id="cancel-button">Cancel</button>
			</div>
			<label id="result-label"></label>
			<script src="../controller/cancelButton.js"></script>
			<?php
				include(".../project_name_here/backend.php"); //UPDATE PATH
				include("../controller/insertController.php"); //UPDATE PATH

			?>
		</main>
		
	</body>
</html>