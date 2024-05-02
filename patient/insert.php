<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define your database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "MHS";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO Patient (FName, MInit, LName, Street, City, State, Zip_Code, Primary_Physician_ID)
         VALUES (?,?, ?, ?, ?, ?, ?, ?)");
    

    // Set parameters and execute
    $patfname = $_POST['patfname'];
    $patminit = $_POST['patminit'];
    $patlname = $_POST['patlname'];
    $patstreet = $_POST['patstreet'];
    $patcity = $_POST['patcity'];
    $patstate = $_POST['patstate'];
    $patzip = $_POST['patzip'];
    $patdocid = $_POST['patdocid'];
    $stmt->bind_param("sssssssi",$patfname, $patminit, $patlname, $patstreet, $patcity, $patstate, $patzip, $patdocid);
    $stmt->execute();

    echo "New records inserted successfully";

    $stmt->close();
    $conn->close();
}
?>
