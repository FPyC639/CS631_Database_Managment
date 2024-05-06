<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        $sql = "SELECT * FROM Patient";
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
<!DOCTYPE html>
<html>
<head>
    <title>View Patient Data</title>
</head>
<body>
    <h2>Patient Table Data</h2>

</body>
</html>
