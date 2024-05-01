<?php
session_start();
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
    // Retrieve form data
    $facstreet = $_POST["facstreet"];
    $faccity = $_POST["faccity"];
    $facstate = $_POST["facstate"];
    $faczip = $_POST["faczip"];
    $facsize = $_POST["facsize"];
    $factype = $_POST["factype"];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; // Replace with your actual password
    $dbname = "MHS";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the Facility table based on the Facility Type
    if ($factype == "Office") {
        $officecount = $_POST["officecount"];
        $sql = "INSERT INTO Facility (Street, City, State, Zip_Code, Size, Facility_Type) 
                VALUES ('$facstreet', '$faccity', '$facstate', '$faczip', '$facsize', 'Office')";
        $sql1 = "Insert"
        
    } elseif ($factype == "OPS") {
        $opsproccode = $_POST["opsproccode"];
        $opsprocdesc = $_POST["opsprocdesc"];
        $opsroomcount = $_POST["opsroomcount"];
        $sql = "INSERT INTO Facility (Facility_Street, Facility_City, Facility_State, Facility_Zip_Code, Facility_Size, Procedure_Code, Procedure_Description, Room_Count) 
                VALUES ('$facstreet', '$faccity', '$facstate', '$faczip', '$facsize', '$opsproccode', '$opsprocdesc', '$opsroomcount')";
    }

    if ($conn->query($sql) === TRUE and ) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>