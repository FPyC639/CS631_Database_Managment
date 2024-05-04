<!DOCTYPE html>
<html>
<head>
    <title>View Appointment Data</title>
</head>
<body>
    <h2>Invoice_Detail Table Data for Selected Patient_ID, Physician_ID, Facility_ID, and Appointment Date & Time</h2>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $selected_pat_id = $_POST["patid"]
        $selected_doc_id = $_POST["docid"]
        $selected_fac_id = $_POST["facid"]
        $selected_date_time = $_POST["apptdatetime"]
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

        // Execute your SELECT statement
        $sql = "SELECT I.Patient_ID as Patient_ID, I.Physician_ID as Physician_ID, I.Facility_ID as Facility_ID, I.Date_Time as Appointment_Date_Time, 
            I.Description as Description, I.Invoice_ID as Invoice_ID, I.Cost as Cost
            FROM Invoice_Detail I WHERE I.Patient_ID = $selected_pat_id AND I.Physician_ID = $selected_doc_id AND 
            I.Facility_ID = $selected_fac_id AND I.Date_Time = $selected_date_time ";
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
        } else {
        echo "Error executing query: " . $conn->error;
        }
        $conn->close();
    }
?>
</body>
</html>
