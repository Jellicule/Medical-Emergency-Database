<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Select Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Select emergencies</h1>
		</header>
		
			<p1>Conditions involving: EmergencyDate, EmergencyDescription, EmergencyLocation, CivilianID, HospitalName, HospitalStayLength, IsDischarged(0/1)</p1><br></br>
		
		<main>
			<form method="GET" action="./select.php">
				<input type="hidden" id="selectRequest" name="selectRequest">
				<div class="field-inputs">

					<div class="labelled-input">
						<label for="condition1-input">Condition 1</label>
						<input id="condition1-input" name  ="condition1-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="and1-input">AND</label>
						<input id="and1-input" name="and1-input" type="checkbox" value=" AND "></input>
					</div>
					<div class="labelled-input">
						<label for="or1-input">OR</label>
						<input id="or1-input" name="or1-input" type="checkbox" value=" OR "></input>
					</div>
					
					<div class="labelled-input">
						<label for="condition2-input">Condition 2</label>
						<input id="condition2-input" name  ="condition2-input" type="text"></input>
					</div>
					<div class="button-panel">
						<button type="submit" id="confirm-button" name="selectEmergency" value="select">Search</button>
					</div>
				</div>
			</form>

			<div class="button-panel">
				<button id="cancel-button">Cancel</button>
			</div>
			<script src="../controller/cancelButton.js"></script>

			<?php 
				include(".../project_name_here/backend.php"); 						// UPDATE PATH
				include(".../project_name_here/controller/insertController.php");	// UPDATE PATH
				
			?>		
		</main>
	</body>
</html>