<!DOCTYPE html>
<html>
<head>
    <title>View Insured_By Data</title>
</head>
<body>
    <h2>Selected Insured_By Table Data</h2>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selected_ins_id = $_POST["insid"];
            $selected_pat_id = $_POST["patid"];
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
            $sql_addtl = null;
            // Execute your SELECT statement
            if ($selected_ins_id === null && $selected_pat_id === null) {
                echo "Neither Insurer ID nor Patient ID was provided. Returning results for entire Insured_By table";
                $sql = "SELECT * FROM Insured_By";
            }
            elseif ($selected_ins_id === null) {
                echo "Selected Patient is insured by the following Insurance Company:";
                $sql = "SELECT Insurer_ID FROM Insured_By WHERE Patient_ID = $selected_pat_id";
            }
            elseif ($selected_pat_id === null) {
                echo "Selected Insurance Company insures the following Patients:";
                $sql = "SELECT Patient_ID FROM Insured_By WHERE Insurer_ID = $selected_ins_id";
            }
            else {
                echo "Selected Insurance Company insures the following Patients:";
                $sql = "SELECT Patient_ID FROM Insured_By WHERE Insurer_ID = $selected_ins_id";
                
                $sql_addtl = "SELECT Insurer_ID FROM Insured_By WHERE Patient_ID = $selected_pat_id";
            }
            $result = $conn->query($sql);

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
            } 
            else {
                echo "<tr><td colspan='" . count($column_names) . "'>0 results</td></tr>";
            }   
            echo "</table>";
            }
            else {
                echo "Error executing query: " . $conn->error;
            }
            if ($sql_addtl !== null) {
                echo "Selected Patient is insured by the following Insurance Company:";
                $result_addtl = $conn->query($sql_addtl);

                // Check if the query was successful
                if ($result_addtl) {
                // Fetch the field metadata for the result set
                $fields_addtl = $result_addtl->fetch_fields();

                // Extract column names from field metadata
                $column_names_addtl = array();
                foreach ($fields_addtl as $field_addt) {
                    $column_names_addtl[] = $field_addtl->name;
                }

                // Output table header 
                echo "<table><tr>";
                foreach ($column_names_addtl as $column_name_addtl) {
                    echo "<th>$column_name_addtl</th>";
                }
                echo "</tr>";

                // Output table rows with data
                if ($result_addtl->num_rows_addtl > 0) {
                    while($row_addtl = $result_addtl->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($column_names_addtl as $column_name_addtl) {
                            echo "<td>" . $row_addtl[$column_name_addtl] . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . count($column_names_addtl) . "'>0 results</td></tr>";
                }   
                echo "</table>";

                }
                else {
                    echo "Error executing query: " . $conn->error;
                }
            }
            $conn->close();
        }
    ?>
</body>
</html>
