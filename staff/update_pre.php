<!DOCTYPE html>
<html>
<head>
    <title>Update Database Values</title>
</head>
<body>
    <h2>Update Employee Salary</h2>
    <?php
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
    $sql = "SELECT * FROM Employee";
    $result = $conn->query($sql);

    // Table header
    echo "<table>
        <tr>
            <th>Employee ID</th>
            <th>SSN</th>
            <th>First Name</th>
            <th>Middle Initial</th>
            <th>Last Name</th>
            <th>Street</th>
            <th>City</th>
            <th>State</th>
            <th>Zip Code</th>
            <th>Salary</th>
            <th>Hire Date</th>
            <th>Job Class</th>
            <th>Facility ID</th>
        </tr>";

    // Display the results in a table
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Employee_ID"] . "</td>";
            echo "<td>" . $row["SSN"] . "</td>";
            echo "<td>" . $row["FName"] . "</td>";
            echo "<td>" . $row["MInit"] . "</td>";
            echo "<td>" . $row["LName"] . "</td>";
            echo "<td>" . $row["Street"] . "</td>";
            echo "<td>" . $row["City"] . "</td>";
            echo "<td>" . $row["State"] . "</td>";
            echo "<td>" . $row["Zip_Code"] . "</td>";
            echo "<td>" . $row["Salary"] . "</td>";
            echo "<td>" . $row["Hire_Date"] . "</td>";
            echo "<td>" . $row["Job_Class"] . "</td>";
            echo "<td>" . $row["Facility_ID"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='13'>0 results</td></tr>";
    }

    // Close the table
    echo "</table>";

    $conn->close();
    ?>
    <form method="post" action="update.php">
        Employee ID: <input type="text" name="employee_id"><br><br>
        New Salary: <input type="text" name="new_salary"><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
