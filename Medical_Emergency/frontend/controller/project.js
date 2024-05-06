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
		const fieldInput = '<input name="' + curField.toLowerCase() + '-field-select" value=' + curField + '" type="checkbox">' + curField + '</input>';
		console.log(fieldInput);
		fieldSelect.insertAdjacentHTML('beforeend', fieldInput);
	}
});