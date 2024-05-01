<?php
// Start the session
session_start();

// MySQL database connection parameters
$hostname = "localhost"; // Change this to your MySQL server hostname
$username_db = "root"; // Change this to your MySQL username
$password_db = ""; // Change this to your MySQL password
$database_name = "MHS"; // Change this to your MySQL database name

try {
    // Connect to MySQL database using PDO
    $dsn = "mysql:host=$hostname;dbname=$database_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $conn = new PDO($dsn, $username_db, $password_db, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to retrieve the last inserted ID and store it in session
function storeLastInsertedID($conn) {
    $_SESSION['last_inserted_id'] = $conn->lastInsertId();
}

// Function to get the last inserted ID from session
function getLastInsertedID() {
    return $_SESSION['last_inserted_id'];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your database insertion code here
    
    // Retrieve the last inserted ID
    storeLastInsertedID($conn);

    // Use the last inserted ID for subsequent insertions
    $lastInsertedID = getLastInsertedID();
    
    //Vars
    $empssn = $_POST["empssn"];
    $empfname = $_POST["empfname"];
    $empmi = $_POST['empmi'];
    $emplname = $_POST['emplname'];
    $empstreet = $_POST['empstreet'];
    $empcity = $_POST['empcity'];
    $empstate = $_POST['empstate'];
    $empzip = $_POST['empzip'];
    $empsalary = $_POST['empsalary'];
    $emphired = $_POST['emphired'];
    $empfac = $_POST['empfac'];
    $emptitle = $_POST['empadmin'];
    $empsp = $_POST['empsp'];
    $empbcd = $_POST['empbdcert'];
    $empjobclass = $_POST['empjobclass'];
    // Example: Use $lastInsertedID in your subsequent insertions
    $sql = "INSERT INTO Employee (
        Employee_ID, SSN, FName, MInit, LName,
        Street, City, State, Zip_Code,
        Salary, Hire_Date, Job_Class, Facility_ID)
    VALUES (:empid, :empssn, :empfname, :empmi, :emplname,
            :empstreet, :empcity, :empstate, :empzip,
            :empsalary, :emphired, :empjobclass, :empfac)";

// Prepare SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bindParam(':empid', $lastInsertedID, PDO::PARAM_INT); // Assuming $lastInsertedID is an integer
$stmt->bindParam(':empssn', $empssn, PDO::PARAM_STR);
$stmt->bindParam(':empfname', $empfname, PDO::PARAM_STR);
$stmt->bindParam(':empmi', $empmi, PDO::PARAM_STR);
$stmt->bindParam(':emplname', $emplname, PDO::PARAM_STR);
$stmt->bindParam(':empstreet', $empstreet, PDO::PARAM_STR);
$stmt->bindParam(':empcity', $empcity, PDO::PARAM_STR);
$stmt->bindParam(':empstate', $empstate, PDO::PARAM_STR);
$stmt->bindParam(':empzip', $empzip, PDO::PARAM_STR);
$stmt->bindParam(':empsalary', $empsalary, PDO::PARAM_STR);
$stmt->bindParam(':emphired', $emphired, PDO::PARAM_STR);
$stmt->bindParam(':empjobclass', $empjobclass, PDO::PARAM_STR);
$stmt->bindParam(':empfac', $empfac, PDO::PARAM_STR);

// Execute SQL statement
$stmt->execute();


    switch ($empjobclass) {
        case "HCP":
// Assuming $conn is your PDO connection

// SQL query
$sql1 = "SELECT Employee_ID FROM Employee WHERE Job_Class='HCP'";

// Prepare SQL statement
$stmt = $conn->prepare($sql1);

// Execute SQL statement
$stmt->execute();

// Fetch all results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if results were found
if ($results) {
    // Get the last result
    $lastResult = end($results);
    
    // Get the Employee_ID from the last result
    $lastEmployeeID = $lastResult['Employee_ID'];
    
    // Output the last Employee_ID
    echo "Last Employee ID where Job_Class='HCP': $lastEmployeeID";
} else {
    echo "No results found where Job_Class='HCP'";
}

// Close the statement
$stmt->closeCursor();

            

            $sql = "INSERT INTO Other_HCP (Employee_ID, Job_Title) VALUES (:empid, :emptitle)";

        // Prepare SQL statement
            $stmt = $conn->prepare($sql);
        // Bind parameters
            $stmt->bindParam(':empid', $lastEmployeeID, PDO::PARAM_INT); // Assuming $lastInsertedID is an integer
            $stmt->bindParam(':emptitle', $emptitle, PDO::PARAM_STR);
            $stmt->execute();
            break;
        case "MD":
            $sql1 = "SELECT Employee_ID FROM Employee WHERE Job_Class='MD'";

// Prepare SQL statement
$stmt = $conn->prepare($sql1);

// Execute SQL statement
$stmt->execute();

// Fetch all results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if results were found
if ($results) {
    // Get the last result
    $lastResult = end($results);
    
    // Get the Employee_ID from the last result
    $lastEmployeeID = $lastResult['Employee_ID'];
    
    // Output the last Employee_ID
    echo "Last Employee ID where Job_Class='MD': $lastEmployeeID";
} else {
    echo "No results found where Job_Class='MD'";
}

// Close the statement
$stmt->closeCursor();
            $sql = "INSERT INTO Doctor (Employee_ID, Specialty, Board_Certification_Date) VALUES (:empid, :empsp, :empbcd)";

    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':empid', $lastEmployeeID, PDO::PARAM_INT); // Assuming $lastInsertedID is an integer
    $stmt->bindParam(':empsp', $empsp, PDO::PARAM_STR);
    $stmt->bindParam(':empbcd', $empbcd, PDO::PARAM_STR);

    // Execute SQL statement
    $stmt->execute();
            break;
        case "Nurse":
            $sql1 = "SELECT Employee_ID FROM Employee WHERE Job_Class='Nurse'";

// Prepare SQL statement
$stmt = $conn->prepare($sql1);

// Execute SQL statement
$stmt->execute();

// Fetch all results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if results were found
if ($results) {
    // Get the last result
    $lastResult = end($results);
    
    // Get the Employee_ID from the last result
    $lastEmployeeID = $lastResult['Employee_ID'];
    
    // Output the last Employee_ID
    echo "Last Employee ID where Job_Class='Nurse': $lastEmployeeID";
} else {
    echo "No results found where Job_Class='Nurse'";
}

// Close the statement
$stmt->closeCursor();
            $sql = "INSERT INTO Nurse (Employee_ID, Certification) VALUES (:empid, :empcert)";

            // Prepare SQL statement
            $stmt = $conn->prepare($sql);
        
            // Bind parameters
            $stmt->bindParam(':empid', $lastResult, PDO::PARAM_INT); // Assuming $lastInsertedID is an integer
            $stmt->bindParam(':empcert', $empcert, PDO::PARAM_STR);
        
            // Execute SQL statement
            $stmt->execute();
            
            break;
        case "Admin":
            $sql1 = "SELECT Employee_ID FROM Employee WHERE Job_Class='Admin'";

            // Prepare SQL statement
            $stmt = $conn->prepare($sql1);
            
            // Execute SQL statement
            $stmt->execute();
            
            // Fetch all results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Check if results were found
            if ($results) {
                // Get the last result
                $lastResult = end($results);
                
                // Get the Employee_ID from the last result
                $lastEmployeeID = $lastResult['Employee_ID'];
                
                // Output the last Employee_ID
                echo "Last Employee ID where Job_Class='Admin': $lastEmployeeID";
            } else {
                echo "No results found where Job_Class='Admin'";
            }
            
            // Close the statement
            $stmt->closeCursor();
            $sql = "INSERT INTO Admin (Employee_ID, Job_Title) VALUES (:empid, :emptitle)";

    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':empid', $lastEmployeeID, PDO::PARAM_INT); // Assuming $lastInsertedID is an integer
    $stmt->bindParam(':emptitle', $emptitle, PDO::PARAM_STR);

    // Execute SQL statement
    $stmt->execute();
            break;
        default:
            // Default table if no match is found
            $tableName = "default_table";
    }
    
}
?>