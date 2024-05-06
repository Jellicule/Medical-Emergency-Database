<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<title>911 Operator Database: Project Emergencies</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<header>
			<h1>Project emergencies</h1>
		</header>
		<main>
			<form method="GET" action="./project.php">
				<input type="hidden" id="projectRequest" name="projectRequest">
				<div class="field-inputs">
					<div class="labelled-input">
						<label for="relation-input">Drop Down</label>
						<select id="relation-input" name  ="table">
							<option name="HospitalSends" value="HospitalSends">HospitalSends</option>
							<option name="StaffWorks" value="StaffWorks">StaffWorks</option>
							<option name="Responds" value="Responds">Responds</option>
							<option name="OtherEmergencyResponders" value="OtherEmergencyResponders">OtherEmergencyResponders</option>
							<option name="Dispatch" value="Dispatch">Dispatch</option>
							<option name="Hospital" value="Hospital">Hospital</option>
							<option name="Staff" value="Staff">Staff</option>
							<option name="Ambulance" value="Ambulance">Ambulance</option>
							<option name="MedicalEmergency" value="MedicalEmergency">MedicalEmergency</option>
							<option name="Caller" value="Caller">Caller</option>
							<option name="Civilians" value="Civilians">Civilians</option>
							<option name="ReceivesCall" value="ReceivesCall">ReceivesCall</option>
							<option name="ContactOtherEmergencyResponders" value="ContactOtherEmergencyResponders">ContactOtherEmergencyResponders</option>
							<option name="ContactsHospital" value="ContactsHospital">ContactsHospital</option>
						</select>
					</div>
					<div id="field-select" class="labelled-input">
						<input id="x-field-select" name="x-field-select" type="checkbox">Fields available for each table will appear here</input>
					</div>
					<div class="button-panel">
						<button id="confirm-button" name="project" >Apply and view</button>
					</div>
				</div>
			</form>
			<div class="button-panel">
				<button id="cancel-button">Back</button>
			</div>
			<script src="/home/j/jettsl/cs304/Medical_Emergency_project/project_name_here/frontend/controller/cancelButton.js"></script>
			<!-- <script src="/home/j/jettsl/public_html/project.js"></script> -->
			<script language = "JavaScript">

				const fields = new Map();

				fields.set("HospitalSends", new Array("HospitalName", "AmbulanceVehicleID"));
				fields.set("StaffWorks", new Array("HospitalName", "StaffID"));
				fields.set("Responds", new Array("EmergencyDate", "EmergencyDescription", "CivilianID", "EmergencyLocation"));
				fields.set("OtherEmergencyResponders", new Array("Responder_Type", "Responder_Location"));
				fields.set("Dispatch", new Array("Region"));
				fields.set("Hospital", new Array("Name", "Address"));
				fields.set("Staff", new Array("StaffID", "Specialty", "HospitalName"));
				fields.set("Ambulance", new Array("VehicleID", "Model", "HospitalName"));
				fields.set("MedicalEmergency", new Array("EmergencyDate", "EmergencyDescription", "EmergencyLocation", "CivilianID", "HospitalName", "HospitalStayLength", "IsDischarged"));
				fields.set("Caller", new Array("CivilianID", "PhoneNumber", "PhoneSerialNumber"));
				fields.set("Civilians", new Array("CivilianID", "Name"));
				fields.set("ReceivesCall", new Array("CivilianID", "PhoneNumber", "DispatchRegion"));
				fields.set("ContactOtherEmergencyResponders", new Array("OtherEmergencyRespondersLocation", "OtherEmergencyRespondersType", "DispatchRegion"));
				fields.set("ContactsHospital", new Array("HospitalName", "DispatchRegion"));

				const relationInput = document.getElementById("relation-input");

				relationInput.addEventListener("change", (event) => {
					const relationName = `${event.target.value}`;
					const curFields = fields.get(relationName);
					const fieldSelect = document.getElementById("field-select");

					// Clears previous fields before inserting new ones:
					while (fieldSelect.firstChild) {
						fieldSelect.removeChild(fieldSelect.lastChild);
					}

					console.log(fields);
					console.log(relationName);
					console.log(curFields);

					for (const curField of curFields) {
						const fieldInput = '<input name="' + curField.toLowerCase() + '-field-select" value=' + curField + ' type="checkbox">' + curField + '</input>';
						console.log(fieldInput);
						fieldSelect.insertAdjacentHTML('beforeend', fieldInput);
					}
				});


			</script>
			<?php
				include("../project_name_here/backend.php");								//CHANGE PATH
				include("../controller/insertController.php");	//CHANGE PATH
				
			?>
	</body>
</html>