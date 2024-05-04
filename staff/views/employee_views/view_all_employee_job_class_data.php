<!DOCTYPE html>
<html>
<head>
    <title>View Employee Data</title>
</head>
<body>
    <h2>Employee Table Data for Selected Job Class</h2>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_job_class = $_POST["empjobclass"];

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

        // Execute the SELECT statement
        if ($selected_job_class == "HCP") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, O.Job_Title as HCP_Job_Title
            FROM Employee E, Other_HCP O
            WHERE E.Employee_ID = O.Employee_ID AND E.Job_Class = '$selected_job_class'";
        }
        elseif ($selected_job_class == "MD") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, D.Specialty as Specialty, D.Board_Certification_Date as Board_Certification_Date
            FROM Employee E, Doctor D 
            WHERE E.Employee_ID = D.Employee_ID AND E.Job_Class = '$selected_job_class'";
        }
        elseif ($selected_job_class == "Nurse") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, N.Certification as Certification
            FROM Employee E, Nurse N
            WHERE E.Employee_ID = N.Employee_ID AND E.Job_Class = '$selected_job_class'";
        }
        // default is Admin job class
        else {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, A.Job_Title as Admin_Job_Title
            FROM Employee E, Admin A
            WHERE E.Employee_ID = A.Employee_ID AND E.Job_Class = '$selected_job_class'";
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
            } 
            else {
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
