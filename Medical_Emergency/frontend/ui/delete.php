<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Delete Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Delete emergencies</h1>
		</header>
		<main>
			<label class="instruction">
				Emergencies Currently registered:
			</label>
			<?php 

			include(".../project_name_here/backend.php"); 		// CHANGE PATH TO YOUR DIRECTORIES

			// Get the tuples to display
			connectToDB();
			$tuples = selectionMedicalEmergencyAll();
			disconnectFromDB();

			//Column names of table
				echo "<table>";
				echo "<tr>";								
					echo "<td>Date</td>";
					echo "<td>Location</td>";
					echo "<td>Address</td>";
					echo "<td>CivilianID</td>";
					echo "<td>Hospital_Name</td>";
					echo "<td>Hospital_Stay_length</td>";
					echo "<td> Discharged_Status </td>";
				echo "</tr>";

				// Iterate through tuples to get the values into the table
				for($x = 0; $x < count($tuples); $x++) {
					$row = $tuples[$x];
				echo "<tr>";								
					echo "<td>". $row[0] . "</td>";
					echo "<td>". $row[1] . "</td>";
					echo "<td>". $row[2] . "</td>";
					echo "<td>". $row[3] . "</td>";
					echo "<td>". $row[4] . "</td>";
					echo "<td>". $row[5] . "</td>";
					echo "<td>". $row[6] . "</td>";
				echo "</tr>";
					}

				echo "</table>";
				
			?>

			<label class="instruction">
				<h3>Please insert the following information:</h3>
			</label>
			<form method="POST" action="./delete.php">
				<input type="hidden" id="deleteRequest" name="deleteRequest">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="civilianid-input">civilianID number</label>
						<input id="civilianid-input" name="civilianid-input" type="text"></input>
					</div>
					<button type="submit" id="confirm-button" name="deleteEmergency" value="delete">Delete Emergency from Database</button>
			</form>

			<div class="button-panel">
				<button id="cancel-button">Cancel</button>
			</div>
			<script src="../controller/cancelButton.js"></script>
			<?php
				include(".../project_name_here/controller/insertController.php");  	// CHANGE PATH 
				include(".../project_name_here/backend.php");						// CHANGE PATH S
			?>
		</main>
	</body>
</html>