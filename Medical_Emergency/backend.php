<?php
	$db_conn = NULL;
	echo ' included back end';

    ////////////////////////////////////
	// FUNCTIONS THAT MAKE STUFF RUN
	///////////////////////////////////
	$show_debug_alert_messages = false;

	function debugAlertMessage($message) {
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function connectToDB() {
		global $db_conn;
		
		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		$db_conn = OCILogon("ora_jettsl", "a23956840", "dbhost.students.cs.ubc.ca:1522/stu");
		
		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For OCILogon errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}
	
	function disconnectFromDB() {
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		OCILogoff($db_conn);
	}
	
    // Used for everything but insert
	function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
		global $db_conn, $success;
		
		$statement = OCIParse($db_conn, $cmdstr);	
		//There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work
	
		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
			echo htmlentities($e['message']);
			$success = False;
		}
		
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
			echo htmlentities($e['message']);
			$success = False;
		}

		return $statement;
	}
	

    // Used for insert
	function executeBoundSQL($cmdstr, $list) {
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
	In this case you don't need to create the statement several times. Bound variables cause a statement to only be
	parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
	See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = OCIParse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}
		
		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				OCIBindByName($statement, $bind, $val);
				unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}
			
			$r = OCIExecute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}	
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// REQUIRED FUNCTIONS: INSERT, SELECTION, PROJECTION, DIVISION, UPDATE, REMOVE, AGG-GROUPBY, AGG-HAVING, AGG-NESTED
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//////////////////////////////////////////
		// INSERT MAIN
		///////////////////////////////////////////

		// $input[0] = EmergencyDate
		// $input[1] = EmergencyDescription
		// $input[2] = EmergencyLocation
		// $input[3] = InjuredPersonCivilianID
		// $input[4] = HospitalName
		// $input[5] = HospitalStayLength
		// $input[6] = IsDischarged
		// $input[7] = Caller.CivilianId
		// $input[8] = Caller.PhoneNumber

		// input: All fields of MedicalEmergency, Caller.CivilianID, Caller.PhoneNumber
        // Modifies: MedicalEmergency, Caller, Injured Person
        // Effects: Creates caller/injured person if they don't exist
		function insertMain($input) {
			global $db_conn;
			
			// Check if the injured person does not exist in the civilian table
			if (!tupleExists("CivilianID = " .$input[3], "Civilians")) {
				// Create the input array for the InjuredPerson_Civilian
				
				$injured_person_civilian_tuple = array();
				array_push($injured_person_civilian_tuple, $input[3], NULL);
				insertCivilian($injured_person_civilian_tuple);
			} else {
				// Some bug report about how the InjuredPersonCivilianID cannot be null
			}
			
			// Check if there is a caller 
			// if ($input[7] != NULL) {
							
			// 	// If there is a caller, then check if the caller does not exisit in the Caller table
            //     // We only need to check the caller is in the Civilian table because Caller ISA Civilian and should already be in the Civilan table
			// 	if (!tupleExists("CivilianId = " .$input[7], "Caller")) {
					
			// 		// Create the input array for the Caller as a Civilan
			// 		$caller_civilian_tuple = array();
			// 		array_push($caller_civilian_tuple, $input[7], NULL);
			// 		insertCivilian($caller_civilian_tuple);

			// 		//Create the input array for the caller as a Caller
			// 		$caller_tuple = array();
			// 		array_push($caller_tuple, $input[7], $input[8], NULL);
			// 		insertCaller($caller_tuple);
			// 	}
			// } 

			// Pop the $input array to get rid of the Caller data
			// array_pop($input);
			// array_pop($input);

			// Check if the civilian is not already having a MedicalEmergency
			if (!tupleExists("CivilianID = " .$input[3], "MedicalEmergency")) {
				insertMedicalEmergency($input);
			} else {
				echo 'That Civilian is already registered as having an emergency';
			}
		}
		
		///////////////////////////////////////////
		// SELECTION ON MEDICAL EMERGENCY (GENERAL)
		////////////////////////////////////////////////

		// Effects: returns an array containing all tuples of MedicalEmergency
        function selectionMedicalEmergencyAll() {
            global $db_conn;
			//create the query
			$query = "SELECT * FROM MedicalEmergency";
            
            return fetchTuples($query);
        }

		///////////////////////////////////////////////////
		// SELECTION ON MEDICALEMERGENCY (WITH input)
		////////////////////////////////////////////////////

		// input: $condition is an array of conditions for the WHERE clause
		// Effects: returns an array of all tuples from MedicalEmergency with a specified $condition
        function selectionMedicalEmergencySpecific($conditions) {
            global $db_conn;
			
			$query = "SELECT * FROM MedicalEmergency WHERE ";
			$query .= $conditions[0];

			// If the conditions contain more than one condition, iterate through the array to concatenate "AND" between them.
			if (count($conditions) > 1) {
				for($x = 1; $x < count($conditions); $x++) {
					$query .= $conditions[$x];
				}
			}      

            return fetchTuples($query);
        }

		/////////////////////////////////////
		// PROJECTION ON MEDICALEMERGENCY
		/////////////////////////////////////
		// Input: $columns is an array with the column names of the table that want to be projected
		//		  $table is a string corresponding to which table we wish to retrieve from 
		// Effects: Returns an array containing all the tuples specified from $columns
		function projectionMain($columns, $table) {
			global $db_conn;

			$select_clause = $columns[0];
			
			// if there is more than 1 column wanting to be projected, iterate throught $columns to concatenate "," in between each column name
			if (count($columns) > 1) {
				for($x = 1; $x < count($columns); $x++) {
					$select_clause .= ", " .$columns[$x];
				}
			}      
			
			$query = "SELECT " .$select_clause. " FROM " .$table;

			return fetchTuples($query);	
		}

		///////////////////////////
		// DELETE ON MEDICALEMERGENCY
		//////////////////////////////

		// Input: CivilianID is an int corresponding to the tuple wanting to be deleted in MedicalEmergency
		// Effects: Deletes the corresponding tuple and cascades to delete caller/injured person
		function deleteMedicalEmergency($civilianID) {
			global $db_conn;

			$query = "DELETE FROM MedicalEmergency WHERE CivilianID = " .$civilianID;
			executePlainSQL($query);
			OCIcommit($db_conn);

		}

		////////////////////////////
		// UPDATE ON MEDICAL EMERGENCY
		///////////////////////////////

		// Input: $civilianID is the identifier for the tuple wanting to be updated
		// 		  $hospitalStayLength is the new value for hospitalStayLength
		// 		  $IsDischarged is the new value for IsDischarged
		// Modifies: MedicalEmergency
		function updateMedicalEmergency($oldHospitalName, $newHospitalName, $hospitalStayLength, $IsDischarged, $civilianID) {
			global $db_conn;
			$query;

			// Fetch the old_hospital_name's address
			$old_hospital_query = "SELECT * from Hospital WHERE Name = '" .$oldHospitalName. "'";
			$old_hospital_tuple = fetchTuples($old_hospital_query);
			$new_hospital = array();

			// Insert a new hospital with the new hospital name and old hospital address
			array_push($new_hospital, $newHospitalName, $old_hospital_tuple[0][1]);
			insertHospital($new_hospital);
			
			// Update each table that has HospitalName as a foreign key
			updateContactsHospital($oldHospitalName, $newHospitalName);
			updateStaffWorks($oldHospitalName, $newHospitalName);
			updateHospitalSends($oldHospitalName, $newHospitalName);
			updateAmbulance($oldHospitalName, $newHospitalName);
			updateStaff($oldHospitalName, $newHospitalName);

			// Update hospitalStayName only
			if ($hospitalStayLength == NULL && $IsDischarged == NULL) {
				$query = "UPDATE MedicalEmergency SET HospitalName = '" .$newHospitalName. "' WHERE CivilianID = '" .$civilianID. "'";

				// Update hospitalStaylength only
			} else if ($hospitalStayLength != NULL && $IsDischarged == NULL) {

				$query = "UPDATE MedicalEmergency SET HospitalName = '" .$newHospitalName. "', HospitalStaylength = " .$hospitalStayLength. " WHERE CivilianID = '" .$civilianID. "'";

				// Update IsDischarged only
			} else if ($hospitalStayLength == NULL && $IsDischarged != NULL) {
				$query = "UPDATE MedicalEmergency SET HospitalName = '" .$newHospitalName. "', IsDischarged = " .$IsDischarged. " WHERE CivilianID = '" .$civilianID. "'";
			} else {
				// Update both
				$query = "UPDATE MedicalEmergency SET HospitalName = '" .$newHospitalName. "', HospitalStaylength = " .$hospitalStayLength. ", IsDischarged = " .$IsDischarged. " WHERE CivilianID = '" .$civilianID. "'";
			}

			executePlainSQL($query);
			OCICommit($db_conn);

			
			$delete_query = "DELETE FROM MedicalEmergency WHERE HospitalName = '" .$oldHospitalName. "'";
			executePlainSQL($delete_query);
			OCICommit($db_conn);
		}

		///////////////////////////////////////
		// JOIN ON MEDICALEMERGENCY AND HOSPITAL
		//////////////////////////////////////////

		// EFFECTS: returns an array containing all MedicalEmergency.addresses that went to which hospital
		function joinMedicalEmeregncyAndHospital($hospitalName) {
			global $db_conn;

			// Create the query
			$query = "SELECT h.Name, h.Address, m.EmergencyLocation FROM MedicalEmergency m, Hospital h WHERE h.Name = '" .$hospitalName. "' AND m.HospitalName = h.Name";
			
			return fetchTuples($query);
		}

		////////////////////////////////
		// AGGREGATION: GROUP BY
		///////////////////////////////

		// Effects: Returns an array containing the number of Staff per specialty
		function aggregationGroupBy($hospitalName) {
			global $db_conn;

			// Create the query
			$query = "SELECT Specialty, COUNT(*) FROM staff WHERE hospitalName = '" .$hospitalName. "' GROUP BY Specialty";

			return fetchTuples($query);
		}

		////////////////////////////////
		// AGGREGATION: HAVING
		///////////////////////////////

		// Date format: YYYY - MM -- DD
		// Input: $lowerDate is the lower bound date
		// 		  $upperDate is the upper bound date
		// Effects: Returns an array containing the number of MedicalEmergencies between 2 dates from each hospital where each hospital has had more than 5 emergencies
    		function aggregationHaving($Input) {
				global $db_conn;       

				$query = "SELECT HospitalName, COUNT(*) as NumEmergencies FROM MedicalEmergency WHERE TO_DATE(EmergencyDate, 'DDMMYYYY') BETWEEN TO_DATE('" . $Input[0] . "', 'DDMMYYYY') AND TO_DATE('" . $Input[1] . "', 'DDMMYYYY') GROUP BY HospitalName HAVING COUNT(*) >= " . $Input[2];
		
		
				return fetchTuples($query);
    		}

		////////////////////////////////
		// AGGREGATION: NESTED
		///////////////////////////////

		// Effects: Returns an array containing the number of patients that stayed in each hospital for a larger than average time
		function aggregationNested() {
			global $db_conn;

			// Create the query
			$nested_query = "(SELECT AVG(HospitalStayLength) FROM MedicalEmergency)";
			$query = "SELECT HospitalName, COUNT(*) FROM MedicalEmergency WHERE HospitalStayLength > " .$nested_query. " GROUP BY HospitalName";  
			
			return fetchTuples($query);
		}

		///////////////////////////
		// DIVISION ON DISPATCHES
		////////////////////////////////

        // Effects: Returns an array with tuples that correspond to the Dispatches that called all OtherEmergencyResponsders
    		function divisionDispatches() {
				global $db_conn;

				// Create the query 
				$query = "SELECT d.Region FROM Dispatch d WHERE NOT EXISTS (SELECT r.Responder_Type, r.Responder_Location FROM OtherEmergencyResponders r WHERE NOT EXISTS (SELECT c.OtherEmergencyRespondersType, c.OtherEmergencyRespondersLocation FROM ContactsOtherEmergencyResponders c WHERE c.OtherEmergencyRespondersType = r.Responder_Type and c.OtherEmergencyRespondersLocation = r.Responder_Location and c.DispatchRegion = d.Region))";
				
				
				return fetchTuples($query);
    		}
	

////////////////////////////////////////////////////////////////////////////////////////////
// HELPERS
///////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////////
		// FETCH TUPLES
		/////////////////////////////
		// Input: $query from which we want to retrieve tuples from
		// Effects: Returns an array containing all tuples resulting from the query
		function fetchTuples($query) {

			$statement = executePlainSQL($query);

			$tuples = array();
			// Input the result into the array
			while (($row = oci_fetch_row($statement)) != false) {
                array_push($tuples, $row);
            }

            return $tuples;
		}
		
		////////////////////////////////
		// TUPLE EXISTS
		//////////////////////////////

		// Input: $condition is the string to put into the WHERE
		// 		  $table is an string that signifies which table to look into 
		// Effects: Returns true if there is a tuple found by the query, false otherwise
		function tupleExists($condition, $table) {
			global $db_conn, $success;

			// Making a query to count the number of tuples from Civilians table with a given CivilianID
			$query = "SELECT COUNT(*) FROM " .$table. " WHERE " .$condition;
			
			$statement = executePlainSQL($query); 
			
			return(oci_fetch_row($statement)[0] == 1);	
		}

		/////////////////////////////
		// UPDATE STAFF
		////////////////////////////
		function updateStaff($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE Staff SET HospitalName = '" .$newHospitalName. "' WHERE HospitalName = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////////
		// UPDATE Ambulance
		////////////////////////////
		function updateAmbulance($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE Ambulance SET HospitalName = '" .$newHospitalName. "' WHERE HospitalName = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////////
		// UPDATE Ambulance
		////////////////////////////
		function updateHospitalSends($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE HospitalSends SET HospitalName = '" .$newHospitalName. "' WHERE HospitalName = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////////
		// UPDATE Ambulance
		////////////////////////////
		function updateStaffWorks($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE StaffWorks SET HospitalName = '" .$newHospitalName. "' WHERE HospitalName = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////////
		// UPDATE Ambulance
		////////////////////////////
		function updateContactsHospital($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE ContactsHospital SET HospitalName = '" .$newHospitalName. "' WHERE HospitalName = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////////
		// UPDATE HOSPITAL
		////////////////////////////

		// Input: $oldHospitalName
		//		  $newHospitalName
		// Modifies: Hospital
		function updateHospital($oldHospitalname, $newHospitalName) {
			global $db_conn;
			$query;
			
			$query = "UPDATE Hospital SET Name = '" .$newHospitalName. "' WHERE Name = '" .$oldHospitalname. "'";
			
			executePlainSQL($query);
			
			OCICommit($db_conn);
		}

		/////////////////////////
		// INSERT ON MEDICAL EMERGENCY
		////////////////////////

        // input: $input[0] = EmergencyDate 
        //        $input[1] = EmergencyDescription
        //        $input[2] = EmergencyLocation
        //        $input[3] = CivilianID
        //        $input[4] = HospitalName
        //        $input[5] = HospitalStayLength
        //        $input[6] = IsDischarged

		// input: All fields of MedicalEmergency, Caller.CivilianID, Caller.PhoneNumber, InjuredPerson.CivilianID
        // Modifies: MedicalEmergency, Caller, Injured Person
        // Effects: Creates caller/injured person if they don't exist
        function insertMedicalEmergency($input) {
            global $db_conn;

            // Create a medicalEmergency tuple with an array using data from user input 
            $medicalEmergencyTuple = array (
                ":EmergencyDate" => $input[0],
                ":EmergencyDescription" => $input[1],
                ":EmergencyLocation" => $input[2],
                ":CivilianID" => $input[3],
                ":HospitalName" => $input[4],
				":HospitalStayLength" => $input[5], 
				":IsDischarged" => $input[6],                 
            );

            // J: Not exactly sure why we need a 2D array for a single tuple
            $allMedicalEmergencyTuples = array (
                $medicalEmergencyTuple
            );

            executeBoundSQL("insert into MedicalEmergency values (:EmergencyDate, :EmergencyDescription, :EmergencyLocation, :CivilianID, :HospitalName, :HospitalStayLength, :IsDischarged)", $allMedicalEmergencyTuples);
            OCICommit($db_conn);
        }

		///////////////////////////////
		// INSERT ON CIVILIAN
		/////////////////////////////////

		// input: $input[0] = Civilian.CivilianID NOT NULL, 
        //        $input[1] = Name
		// Modifies: Civilians 
		function insertCivilian($input) {
			global $db_conn;

			// // Create a Civilian tuple with an array using data from user input 
			$civilianTuple = array (
				":CivilianID" => $input[0],
				":Name" => $input[1],
			);

			$allCivilianTuples = array (
				$civilianTuple
			);

			executeBoundSQL("INSERT INTO Civilians VALUES (:CivilianID, :Name)", $allCivilianTuples);
			OCICommit($db_conn);
		}

		///////////////////////////////
		// INSERT ON CALLER
		/////////////////////////////////

		// input: $input[0] = Caller.CivilianID NOT NULL, 
        //        $input[1] = PhoneNumber
        //        $input[2] = PhoneSerialNumber
		// Modifies: Caller 
		function insertCaller($input) {
			global $db_conn;

			// Create a Caller tuple with an array using data from user input 
			$callerTuple = array (
				":CivilianID" => $input[0],
				":PhoneNumber" => $input[1],
				":PhoneSerialNumber" => $input[2],
			);

			$allCallerTuples = array (
				$callerTuple
			);

			executeBoundSQL("INSERT INTO Caller VALUES (:CivilianID, :PhoneNumber, :PhoneSerialNumber)", $allCallerTuples);
			OCICommit($db_conn);
		}
		
		////////////////////////////////
		// INSERT ON HOSPITAL
		///////////////////////////////

        // Input: $input[0] = HospitalName
        //        $input[1] = address
        // Modifies: Hospital
		function insertHospital($input) {
			global $db_conn;

			// Create a Hospital tuple with an array using data from user input 
			$hospitalTuple = array (
				":Name" => $input[0],
				":Address" => $input[1],
			);

			$allHospitalTuples = array (
				$hospitalTuple
			);

			executeBoundSQL("INSERT INTO Hospital VALUES (:Name, :Address)", $allHospitalTuples);
			OCICommit($db_conn);
		}

	
		
?>

