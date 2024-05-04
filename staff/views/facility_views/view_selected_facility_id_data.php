<!DOCTYPE html>
<html>
<head>
    <title>View Facility Data</title>
</head>
<body>
    <h2>Facility Table Data for Selected Facility_ID</h2>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_fac_id = $_POST["facid"];

        // Connect to your database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "MHS";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql_initial = "SELECT F.Facility_ID as Facility_ID FROM Facility F WHERE F.Facility_ID = $selected_fac_id";

        $res_initial = $conn->query($sql_initial);
        echo "Facility ID = $selected_fac_id";
        $facility_type_value = null;
        // Check if the query was successful
        if ($res_initial) {
            // Fetch the result as an associative array
            $row_initial = $res_initial->fetch_assoc();

            // Access the value of the 'Job_Class' column from the fetched row
            $facility_type_value = $row_initial['Facility_Type'];
        } else {
            echo "Error executing query: " . $conn->error;
        }
        // Execute the SELECT statement
        if ($job_class_value === null) {
            echo "Facility Type value is null.";
        } 
        elseif ($facility_type_value == "Office") {
            $sql = "SELECT F.Facility_ID as Facility_ID, F.Street as Street, F.City as City,
            F.State as State, F.Zip_Code as Zip_Code, F.Size as Size,
            F.Facility_Type as Facility_Type, O.Office_Count as Office_Count
            FROM Facility F, Office O
            WHERE F.Facility_ID = O.Facility_ID AND F.Facility_ID = $selected_fac_id AND F.Facility_Type = '$facility_type_value'";
        }
        // default is Outpatient_Surgery
        else {
            $sql = "SELECT F.Facility_ID as Facility_ID, F.Street as Street, F.City as City,
            F.State as State, F.Zip_Code as Zip_Code, F.Size as Size,
            F.Facility_Type as Facility_Type, O.Procedure_Code as Procedure_Code,
            O.Procedure_Description as Procedure_Description, O.Room_Count as Room_Count
            FROM Facility F, Outpatient_Surgery O
            WHERE F.Facility_ID = O.Facility_ID AND F.Facility_ID = $selected_fac_id AND F.Facility_Type = '$facility_type_value'";
        }
        
        $result = $conn->query($sql);
        $column_names = array();

        // Check if the query was successful
        if ($result) {
            // Fetch the field metadata for the result set
            $fields = $result->fetch_fields();

            // Extract column names from field metadata
            $column_names = array();
            foreach ($fields as $field) {
                $column_names[] = $field->name;
            }

            // Output table header 
            echo "<table><tr>";
            foreach ($column_names as $column_name) {
                echo "<th>$column_name</th>";
            }
            echo "</tr>";

            // Output table rows with data
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($column_names as $column_name) {
                        echo "<td>" . $row[$column_name] . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='" . count($column_names) . "'>0 results</td></tr>";
            }   
            echo "</table>";
        } 
        else {
            echo "Error executing query: " . $conn->error;
        }
        $conn->close();
    }
?>
</body>
</html>
