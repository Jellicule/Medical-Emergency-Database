<!-- 
To access my little test thing:
https://www.students.cs.ubc.ca/~jettsl/project-test.php

I am not sure how to connect our SQL file to this
I am not sure if any of this works cuz I don't know how to test it

These are just functions that I think will work based on my understanding from the demoTable example they gave us

Some comments may start with ( J: ) those are my thoughts and questions regarding the stuff below it
-->
<html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>
    <body>
        <hr />

            <h2>Input Information regarding an Injured Person</h2>
            <form method="Input" action="project-test.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                Date: <input type="date" name="date"> <br /><br />
                Description: <input type="text" name="description"> <br /><br />
                Location: <input type="text" name="location"> <br /><br />
                Civilian ID: <input type="number" name="civilianID"> <br /><br />
                Hospital Name: <input type="text" name="hospitalname"> <br /><br />
                Hospital Stay Length: <input type="text" name="staylength"> <br /><br />
                <p>Discharged Status: </p>
                <p style="margin-left: 25px;">
                Yes: <input type="checkbox" name="discharged" value = "True"> <br /> <br />
                No: <input type="checkbox" name="discharged" value = "False"> <br /> 
                </p>

                <input type="submit" value="Submit" name="insertSubmit"></p>
            </form>

            <hr />


        <?php

        $success = True;
        $db_conn = NULL;
        
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

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
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
                    //echo $val;
                    //echo "<br>".$bind."<br>";
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

        // J: Use the result of this to print out the tables of our data
        // Effects: returns an array containing all tuples of MedicalEmergency
        function selectionMedicalEmergencyAll() {
            global $db_conn;
            // Parse and excute the query
            $result = executePlainSQL("SELECT * FROM MedicalEmergency");
            OCICommit($db_conn);
    
            // Insert each row of the MedicalEmergency table reuslting from the query into the array $tuples
            $tuples;
            while ($row = OCI_Fetch_Array($result, OCI_BOTH) != false) {
                array_push($tuples, $row);
            }

            return $tuples;
        }

        // J: Possibly iterate through $condition[array?][string?] to concatenate "AND" in between each condition if size_of($condiiton) > 1
        // Effects: returns an array all tuples from MedicalEmergency with a specified $condition
        function selectionMedicalEmergencySpecific($condition) {
            global $db_conn;
            // Dpes the $elements require commas??
            $query = "SELECT * FROM MedicalEmergency WHERE " . $condition .;

            // Parse and excute the query
            $result = executePlainSQL($query);
            OCICommit($db_conn);

            // Insert each row of the table reuslting from the query into the array $tuples
            $tuples;
            while ($row = OCI_Fetch_Array($result, OCI_BOTH) != false) {
                array_push($tuples, $row);
            }
            
            return $tuples;
        }

        // J: Possibly iterate through $elements[array?][string?] to concatenate "," in between each element if size_of($elements) > 1
        function projectionMedicalEmergency($elements) {
            global $db_conn;

            $query = "SELECT " .$elements. " FROM MedicalEmergency";

            // Parse and excute the query
            $result = executePlainSQL($query);
            OCICommit($db_conn);

            // Insert each row of the table reuslting from the query into the array $tuples
            $tuples;
            while ($row = OCI_Fetch_Array($result, OCI_BOTH) != false) {
                array_push($tuples, $row);
            }
            
            return $tuples;
        }

        // Input: All fields of MedicalEmergency, Caller.CivilianID, Caller.PhoneNumber, InjuredPerson.CivilianID
        // Modifies: MedicalEmergency, Caller, Injured Person
        // Effects: Creates caller/injured person if they don't exist
        function insertMedicalEmergency() {
            global $db_conn;

            // Create a medicalEmergency tuple with an array using data from user input 
            // J: not exactly sure if the key names ":bind1" etc. matter 
            $medicalEmergencyTuple = array (
                ":bind1" => $_Input['date'],
                ":bind2" => $_Input['description'],
                ":bind3" => $_Input['location'],
                ":bind4" => $_Input['injuredPersonCivilianID'],
                ":bind5" => $_Input['hospitalName'],
                ":bind6" => $_Input['stayLength'],
                ":bind7" => $_Input['discharged'],
                ":bind8" => $_Input['callerCivilianID'],
                ":bind9" => $_Input['phoneNumber'],
            );

            // J: Not exactly sure why we need a 2D array for a single tuple
            $allMedicalEmergencyTuples = array (
                $medicalEmergencyTuple
            );


            // Execute the insertion
            executeBoundSQL("insert into MedicalEmergency values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9)", $allMedicalEmergencyTuples);
            OCICommit($db_conn);

            // If an injuredPerson.Civilian does not exist, insert this new injured person into the Civilian table
            // J: Do I need to make a helper? doesCivilianExist()? Would have to create selectionCivilian() helper to clarify 
            if ( ) {
                insertCivilian($_Input['injuredPersonCivilianID']);
            }

            // If a caller does not exist, insert this new caller into the Civilian and Caller tables
            if ( ) {
                insertCivilian($_Input['callerCivilianID']);
                insertCaller($_Input['callerCivilianID'], $_Input['phoneNumber']);
            }
            
        }

        // Input: InjuredPerson.CivilianID
        // Modifies: InjuredPerson 
        function insertCivilian($civilianID) {
            global $db_conn;

            // Create a Civilian tuple with an array using data from user input 
            $civilianTuple = array (
                ":bind1" => $civilianID,
                ":bind2" => NULL;
            );

            $allCiviianTuples = array (
                $civilianTuple
            );

            executeBoundSQL("INSERT INTO Civilians VALUES (:bind1, :bind2)", $allCivilianTuples);
            OCICommit($db_conn);
        }

        // Input: Caller.CivilianID, Caller.phoneNumber
        // Modifies: Caller
        function insertCaller($civilianID, $phoneNumber) {
            global $db_conn;

            // Create a Caller tuple with an array using data from user input 
            $callerTuple = array (
                ":bind1" => $civilianID,
                ":bind2" => $phoneNumber;
                ":bind3" => NULL;
            );

            $allCallerTuples = array (
                $callerTuple
            );

            executeBoundSQL("INSERT INTO Caller VALUES (:bind1, :bind2, :bind3)", $allCivilianTuples);
            OCICommit($db_conn);
        }

        // Input: Selection from list of emergencies
        // Effects: Detels MedicalEmergency, cascades to delete caller/injured person
        function deleteMedicalEmergency() {
            // Delete based on civilianID
            global $db_conn;

            $statementMedicalEmergency = "DELETE FROM MedicalEmergency WHERE civilianID = " $_Delete['injuredPersonCivilianID'];
            executePlainSQL($db_conn, $statementMedicalEmergency);

            $statementCivlian = "DELETE FROM Civilian WHERE civilianID = " $_Delete['injuredPersonCivilianID'];
            executePlainSQL($db_conn, $statementCivilian);

            // J: If i try to delete a tuple that isn't on the Caller table, will it throw an error?
            $statementCaller = "DELETE FROM Caller WHERE civilianID = " $_Delete['callerCivilianID'];
            executePlainSQL($db_conn, $statementCaller);

            OCICommit($db_conn);
        }


        // HANDLE ALL Input ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleInputRequest() {
            if (connectToDB()) {
                if (array_key_exists('insertQueryRequest', $_Input)) {
                    insertMedicalEmergency();
                }
                disconnectFromDB();
            }
        }

        if (isset($_Insert['insertSubmit'])) {
            handleInputRequest();
        } 

        
        ?>
    </body>
</html>
