<?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $employee_id = $_POST["employee_id"];
        $new_salary = $_POST["new_salary"];

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

        // Get old values
        $old_values = [];
        $sql_old = "SELECT * FROM Employee WHERE Employee_ID='$employee_id'";
        $result_old = $conn->query($sql_old);
        if ($result_old->num_rows > 0) {
            $row_old = $result_old->fetch_assoc();
            $old_values = $row_old;
        }

        // Update database values
        $sql_update = "UPDATE Employee SET Salary='$new_salary' WHERE Employee_ID='$employee_id'";
        $result_update = $conn->query($sql_update);

        // Get updated values
        $updated_values = [];
        $sql_updated = "SELECT * FROM Employee WHERE Employee_ID='$employee_id'";
        $result_updated = $conn->query($sql_updated);
        if ($result_updated->num_rows > 0) {
            $row_updated = $result_updated->fetch_assoc();
            $updated_values = $row_updated;
        }

        // Print old values
        echo "<h2>Old Values</h2>";
        echo "<pre>";
        print_r($old_values);
        echo "</pre>";

        // Print updated values
        echo "<h2>New Values</h2>";
        echo "<pre>";
        print_r($updated_values);
        echo "</pre>";

        $conn->close();
    }
    ?>