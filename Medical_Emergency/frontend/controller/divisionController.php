<?php
if (isset($_GET['division'])) {
	handleDivisionRequest();
} else {
	echo 'Click view to send request';
}

function handleDivisionRequest() {
	if(connectToDB()) {
		if(array_key_exists('division', $_GET)) {
			connectToDB();

			$tuples = divisionDispatches();
			
			disconnectFromDB();

			echo "<table>";
            echo "<tr>";								
                echo "<td>Call centre</td>";
            
            echo "</tr>";

            // Iterate through tuples to get the values into the table
            for($x = 0; $x < count($tuples); $x++) {
                $row = $tuples[$x];
            echo "<tr>";								
                echo "<td>". $row[0] . "</td>";
            echo "</tr>";
                }

            echo "</table>";
		} 
	} else {
		echo 'failed to connect';
	}
}

?>