<?php

if (isset($_POST['insertEmergency']) || isset($_POST['deleteEmergency']) ||isset($_POST['updateEmergency']) || isset($_GET['selectEmergency']) || isset($_GET['project']) || isset($_GET['nested']) || isset($_GET['groupBy']) || isset($_GET['join']) || isset($_GET['having'])) {
	echo '<br> Request detected';
    handleRequest();
    // echo "<meta http-equiv='refresh' content='0'>";
} else {
    echo 'request not detected';
}

function handleRequest() {
    if (connectToDB()) {
 
        if(array_key_exists('insertRequest', $_POST)) {
            $Input = array(
                0 => $_POST['date-input'],
                1 => $_POST['description-input'],
                2 => $_POST['location-input'],
                3 => $_POST['civilian-id-input'],
                4 => $_POST['hospital-name-input'],
                5 => $_POST['hospital-stay-length-input'],
                6 => $_POST['is-discharged-input']
            );
            insertMain($Input);   
            disconnectFromDB();         
        } else if (array_key_exists('deleteRequest', $_POST)) {

            deleteMedicalEmergency($_POST['civilianid-input']);
            disconnectFromDB();

        } else if (array_key_exists('selectRequest', $_GET)) {
            
            $Input = array();

                if($_GET['condition1-input'] != NULL) {
                    array_push($Input, $_GET['condition1-input']);
                }

                if($_GET['and1-input'] != NULL && $_GET['or1-input'] == NULL) {
                    array_push($Input, " AND ");
                } else if ($_GET['and1-input'] == NULL && $_GET['or1-input'] != NULL) {
                    array_push($Input, " OR ");
                } else {
                    echo 'Only one of AND/OR may be selected';
                }

                if ($_GET['condition2-input'] != NULL ) {
                    array_push($Input, $_GET['condition2-input']);
                }
  
            $tuples = selectionMedicalEmergencySpecific($Input);
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
                
        } else if(array_key_exists('updateRequest', $_POST)) {

            updateMedicalEmergency($_POST['old-hospital-name-input'], $_POST['new-hospital-name-input'], $_POST['hospital-stay-length-input'], $_POST['is-discharged-input'],  $_POST['civilianID-input']);

        } else if(array_key_exists('project', $_GET)) {
           
            // Get the column names into $columns
            $columns = array();
            foreach($_GET as $key => $value) {
                if($key == 'projectRequest' || $key == 'table' || $key == 'project') {
                    continue;
                }
                array_push($columns, $value);
            }

            // Get the tuples from the projection query
            $tuples = projectionMain($columns, $_GET['table']);
            disconnectFromDB();

            // Print tuples
            echo '<pre>';
            print_r($tuples);
            echo '</pre>';
        } else if(array_key_exists('nestedRequest', $_GET)) {
            // Get the tuples to display
			connectToDB();
			$tuples = aggregationNested();
			disconnectFromDB();

			//Column names of table
				echo "<table>";
				echo "<tr>";								
					echo "<td>HospitalName</td>";
					echo "<td>Number of patients that stayed longer than average</td>";
				
				echo "</tr>";

				// Iterate through tuples to get the values into the table
				for($x = 0; $x < count($tuples); $x++) {
					$row = $tuples[$x];
				echo "<tr>";								
					echo "<td>". $row[0] . "</td>";
					echo "<td>". $row[1] . "</td>";
				echo "</tr>";
					}

				echo "</table>";

        } else if (array_key_exists('groupbyRequest', $_GET)) {

        connectToDB();
        $tuples = aggregationGroupBy($_GET['hospital-name-input']);
        disconnectFromDB();

        //Column names of table
            echo "<table>";
            echo "<tr>";								
                echo "<td>Speciality</td>";
                echo "<td>Count</td>";
            
            echo "</tr>";

            // Iterate through tuples to get the values into the table
            for($x = 0; $x < count($tuples); $x++) {
                $row = $tuples[$x];
            echo "<tr>";								
                echo "<td>". $row[0] . "</td>";
                echo "<td>". $row[1] . "</td>";
            echo "</tr>";
                }

            echo "</table>";
        } else if(array_key_exists('joinRequest', $_GET)) {
            $tuples = joinMedicalEmeregncyAndHospital($_GET['hospital-name-input']);
            disconnectFromDB();

        //Column names of table
            echo "<table>";
            echo "<tr>";								
                echo "<td>HospitalName</td>";
                echo "<td>HospitalAddress</td>";
                echo "<td>EmergencyAddress</td>";
            
            echo "</tr>";

            // Iterate through tuples to get the values into the table
            for($x = 0; $x < count($tuples); $x++) {
                $row = $tuples[$x];
            echo "<tr>";								
                echo "<td>". $row[0] . "</td>";
                echo "<td>". $row[1] . "</td>";
                echo "<td>". $row[2] . "</td>";
            echo "</tr>";
                }

            echo "</table>";
        } else if(array_key_exists('having', $_GET)) {

            $Input = array (
                0 => $_GET['begin-date-input'],
                1 => $_GET['end-date-input'],
                2 => $_GET['min-emergencies-input']
            );
       
            $tuples = aggregationHaving($Input);
            disconnectFromDB();

            echo "<table>";
            echo "<tr>";								
                echo "<td>Hospital</td>";
                echo "<td>EmergencyCount</td>";

            echo "</tr>";

            // Iterate through tuples to get the values into the table
            for($x = 0; $x < count($tuples); $x++) {
                $row = $tuples[$x];
            echo "<tr>";								
                echo "<td>". $row[0] . "</td>";
                echo "<td>". $row[1] . "</td>";
            echo "</tr>";
                }

            echo "</table>";
        }
    }

}





?>
