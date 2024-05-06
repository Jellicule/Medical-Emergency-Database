<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Update Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Update emergencies</h1>
		</header>
		<main>
			<label class="instruction">
				Please select from the following:
			</label>
			<form method="POST" action="./update.php">
				<input type="hidden" id="updateRequest" name="updateRequest">
				<div class="field-inputs">
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
				</div>
				<label class="instruction">
					<br> Enter the current Hospital_Name corresponding to the row you wish to delete: 
				</label>
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="old-hospital-name-input">Hospital_Name [REQUIRED]</label>
						<input id="old-hospital-name-input" name="old-hospital-name-input" type="text"></input>
					</div>

				<label class="instruction">
				<br> Then, edit the values: 
				</label>
				
					<div class="labelled-input">
						<label for="new-hospital-name-input">New Hospital name [REQUIRED]</label> 
						<input id="new-hospital-name-input" name="new-hospital-name-input" type="text"></input>
					</div>
					<div class="labelled-input">
						<label for="hospital-stay-length-input">Hospital stay length</label>
						<input id="hospital-stay-length-input" name="hospital-stay-length-input" type="number"></input>
					</div>
					<div class="labelled-input">
						<label for="is-discharged-input">Have they been discharged?</label>
						<input id="is-discharged-input" name="is-discharged-input" type="checkbox">
					</div>
					<div class="button-panel">
						<button id="confirm-button" name="updateEmergency" value="Update">Update 
					</div>
				</div>
			</form>

			<div class="button-panel">
				<button id="cancel-button">Cancel</button>
			</div>
			<script src="../controller/cancelButton.js"></script>
			<?php
				include(".../project_name_here/controller/insertController.php");  	// CHANGE PATH
			?>
		</main>
		
	</body>
</html>