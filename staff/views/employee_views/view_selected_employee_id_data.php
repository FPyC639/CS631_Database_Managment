<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_emp_id = $_POST["empid"];

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

        $sql_initial = "SELECT E.Job_Class as Job_Class FROM Employee E WHERE E.Employee_ID = $selected_emp_id";

        $res_initial = $conn->query($sql_initial);
        echo "Employee ID = $selected_emp_id";
        $job_class_value = null;
        // Check if the query was successful
        if ($res_initial) {
            // Fetch the result as an associative array
            $row_initial = $res_initial->fetch_assoc();

            // Access the value of the 'Job_Class' column from the fetched row
            $job_class_value = $row_initial['Job_Class'];
        } else {
            echo "Error executing query: " . $conn->error;
        }
        // Execute the SELECT statement
        if ($job_class_value === null) {
            echo "Job class value is null.";
        } 
        
        elseif ($job_class_value == "HCP") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, O.Job_Title as HCP_Job_Title
            FROM Employee E, Other_HCP O
            WHERE E.Employee_ID = O.Employee_ID AND E.Employee_ID = $empid AND E.Job_Class = '$job_class_value'";
        }
        elseif ($job_class_value == "MD") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, D.Specialty as Specialty, D.Board_Certification_Date as Board_Certification_Date
            FROM Employee E, Doctor D 
            WHERE E.Employee_ID = D.Employee_ID AND E.Employee_ID = $empid AND E.Job_Class = '$job_class_value'";
        }
        elseif ($job_class_value == "Nurse") {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, N.Certification as Certification
            FROM Employee E, Nurse N
            WHERE E.Employee_ID = N.Employee_ID AND E.Employee_ID = $empid AND E.Job_Class = '$job_class_value'";
        }
        // default is Admin job class
        else {
            $sql = "SELECT E.Employee_ID as Employee_ID, E.SSN as SSN, E.FName as First_Name, E.MInit as Middle_Initial, E.LName as Last_Name, 
            E.Street as Street_Address, E.City as City, E.State as State, E.Zip_Code as Zip_Code, E.Salary as Salary, E.Hire_Date as Hire_Date, 
            E.Job_Class as Job_Class, E.Facility_ID as Facility_ID, A.Job_Title as Admin_Job_Title
            FROM Employee E, Admin A
            WHERE E.Employee_ID = A.Employee_ID AND E.Employee_ID = $empid AND E.Job_Class = '$job_class_value'";
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
    echo "</body></html>";
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Employee Data</title>
</head>
<body>
    <h2>Employee Table Data for Selected Employee_ID</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="empid">Employee ID:</label><br>
        <input type="text" id="empid" name="empid"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
