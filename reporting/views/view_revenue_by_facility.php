<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_date = $_POST["invdate"];
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

        // Execute your SELECT statement
        $sql1 = "SELECT ID.Facility_ID as Facility_ID, ID.Patient_ID as Patient_ID, ID.Physician_ID as Physician_ID, ID.Date_Time as Appointment_Date_Time, 
            ID.Cost as Subtotal
            FROM Invoice_Detail ID, Invoice I
            WHERE ID.Invoice_ID = I.Invoice_ID AND DATE(I.Invoice_Date) = '$inv_date'
            ORDER BY ID.Facility_ID";


        $sql2 = "SELECT F.Facility_ID as Facility_ID, F.Street as Street_Address, F.City as City, F.State as State, F.Zip_Code as Zip_Code, 
            SUM(ID.Cost) as Total_Revenue
            FROM Facility F, Invoice_Detail ID, Invoice I
            WHERE F.Facility_ID = ID.Facility_ID AND ID.Invoice_ID = I.Invoice_ID AND DATE(I.Invoice_Date) = '$inv_date'
            GROUP BY F.Facility_ID
            ORDER BY ID.Facility_ID";

        // Print Subtotals
        $result1 = $conn->query($sql1);
        $column_names1 = array();

        // Check if the query was successful
        if ($result1) {
        // Fetch the field metadata for the result set
        $fields1 = $result1->fetch_fields();

        // Extract column names from field metadata
        $column_names1 = array();
        foreach ($fields1 as $field1) {
            $column_names1[] = $field1->name;
        }

        // Output table header 
        echo "<table><tr>";
        foreach ($column_names1 as $column_name1) {
            echo "<th>$column_name1</th>";
        }
        echo "</tr>";

        // Output table rows with data
        if ($result1->num_rows1 > 0) {
            while($row1 = $result1->fetch_assoc()) {
                echo "<tr>";
                foreach ($column_names1 as $column_name1) {
                    echo "<td>" . $row1[$column_name1] . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . count($column_names1) . "'>0 results</td></tr>";
        }   
        echo "</table>";
        } else {
        echo "Error executing query: " . $conn->error;
        }
        // --------------------------------------------------------------------------------
        // Print Totals
        $result2 = $conn->query($sql2);
        $column_names2 = array();

        // Check if the query was successful
        if ($result2) {
        // Fetch the field metadata for the result set
        $fields2 = $result2->fetch_fields();

        // Extract column names from field metadata
        $column_names2 = array();
        foreach ($fields2 as $field2) {
            $column_names2[] = $field2->name;
        }

        // Output table header 
        echo "<table><tr>";
        foreach ($column_names2 as $column_name2) {
            echo "<th>$column_name2</th>";
        }
        echo "</tr>";

        // Output table rows with data
        if ($result2->num_rows2 > 0) {
            while($row2 = $result2->fetch_assoc()) {
                echo "<tr>";
                foreach ($column_names2 as $column_name2) {
                    echo "<td>" . $row2[$column_name2] . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . count($column_names2) . "'>0 results</td></tr>";
        }   
        echo "</table>";
        } else {
        echo "Error executing query: " . $conn->error;
        }
        $conn->close();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Revenue by Facility for Given Day</title>
</head>
<body>
    <h2>Revenue by Facility for Given Day</h2>

</body>
</html>
