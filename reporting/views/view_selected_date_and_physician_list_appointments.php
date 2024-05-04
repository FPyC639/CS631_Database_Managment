<!DOCTYPE html>
<html>
<head>
    <title>View List of Appointments for Selected Date and Physician</title>
</head>
<body>
    <h2>List of Appointments for Selected Date and Physician</h2>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_date = $_POST["apptdate"]
        $selected_doc_id = $_POST["docid"]
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
        $sql = "SELECT Physician_ID, CAST(Date_Time AS DATE) AS Selected_Date, Facility_ID, Patient_ID, Date_Time
            FROM Invoice_Detail
            WHERE Physician_ID = $selected_doc_id AND CAST(Date_Time AS DATE) = '$selected_date'";
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
