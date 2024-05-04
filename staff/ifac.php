<?php
$sql = "Select Facility.Facility_ID, Street, City, State, Zip_Code, Size, Facility_Type,
Office_Count, Procedure_Code, Procedure_Description, Room_Count From Facility
CROSS JOIN Office
LEFT JOIN Outpatient_Surgery
ON Facility.Facility_ID = Office.Facility_ID and
Outpatient_Surgery.Facility_ID = Facility.Facility_ID;";
$result = $conn->query($sql);

// Table header
echo "<table>
    <tr>
        <th>Facility ID</th>
        <th>Street</th>
        <th>City</th>
        <th>State</th>
        <th>Zip Code</th>
        <th>Size</th>
        <th>Facility Type</th>
        <th>Office Count</th>
        <th>Procedure Code</th>
        <th>Procedure Description</th>
        <th>Room Count</th>
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

?>
<?php
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
    $i = "Insert";
    $u = "Update";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if($_POST["operation"] == $i){
    if ($factype == "Office") {
        $officecount = $_POST["officecount"];
        $sql = "INSERT INTO Facility (Street, City, State, Zip_Code, Size, Facility_Type) 
                VALUES ('$facstreet', '$faccity', '$facstate', '$faczip', '$facsize', 'Office')";
        $sql1 = "Insert";
        
    } elseif ($factype == "OPS") {
        $opsproccode = $_POST["opsproccode"];
        $opsprocdesc = $_POST["opsprocdesc"];
        $opsroomcount = $_POST["opsroomcount"];
        $sql = "INSERT INTO Facility (Facility_Street, Facility_City, Facility_State, Facility_Zip_Code, Facility_Size, Procedure_Code, Procedure_Description, Room_Count) 
                VALUES ('$facstreet', '$faccity', '$facstate', '$faczip', '$facsize', '$opsproccode', '$opsprocdesc', '$opsroomcount')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    }
    if($_POST["operation"] = $u){

    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" name="viewport" content="text/html; charset=UTF-8">
	<title>patient.html</title>
    <style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        h1 {
            color: #bb591c;
            margin: 0 0 1.5em 0;
            font-size: 22pt;
            justify-content: center;
            align-items: center;
            margin-bottom: 1em;
            text-align: center;
        }
        p {
            color: #1ab305;
            margin: 0 0 1.5em 0;
            font-size: 12pt;
            justify-content: center;
            align-items: center;
            margin-bottom: 1em;
        }
        label {
            display: block;
            margin-bottom: 0px;
            color: #000000;
        }
        label:hover {
            display: block;
            margin-bottom: 0px;
            color: #000000;
            font-weight: bold;
            cursor: pointer;
        }
        label:has(input:checked) {
            color: #0d00ff;
            font-weight: bold;
        }
        label:hover:has(input:checked) {
            display: block;
            margin-bottom: 0px;
            color: #000000;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="text"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type='reset'] {
            font-family: Georgia, 'Times New Roman', Times, serif;
        	padding: 10px 20px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type='reset']:hover {
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-weight: bold;
        	padding: 10px 20px;
            background-color: #0004ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type='submit'] {
            font-family: Georgia, 'Times New Roman', Times, serif;
        	padding: 10px 20px;
            background-color: #1ab305;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type='submit']:hover {
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-weight: bold;
        	padding: 10px 20px;
            background-color: #0004ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container {
            background-color: rgb(236, 236, 210);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .text_box {
            color: #001a68;
            border: none;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            break-before: always;
            margin: 0 -1.5em 1.5em 0;
        }
    </style>
    <script type="text/javascript">
    function FacTypeCheck(that) {
    	if (that.value == "Office") 
        {
            document.getElementById("Office").style.display = "block";
            document.getElementById("OPS").style.display = "none";
        }
    	else if (that.value == "OPS") 
        {
            document.getElementById("OPS").style.display = "block";
            document.getElementById("Office").style.display = "none";
        }
        else
        {
            document.getElementById("Office").style.display = "none";
            document.getElementById("OPS").style.display = "none";
        }
    }
    function InsertUpdateCheck(that) {
    	if (that.value == "Insert") 
        {
            document.getElementById("Insert").style.display = "block";
            document.getElementById("Update").style.display = "none";
            document.getElementById("MakeVisible").style.display = "block";
        }
    	else if (that.value == "Update") 
        {
            document.getElementById("Update").style.display = "block";
            document.getElementById("Insert").style.display = "none";
            document.getElementById("MakeVisible").style.display = "block";
        }
        else
        {
            document.getElementById("Insert").style.display = "none";
            document.getElementById("Update").style.display = "none";
            document.getElementById("MakeVisible").style.display = "none";
        }
    }
</script>
</head>
<body>
    <div>
        <div>
            <h1>MHS Facility Management</h1>
        </div>
        <div>
            <form name="facility" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	            
	            <p><input type = "hidden" name = "table-name" value = "facility" /></p>
	            <p> Select an option from the dropdown: 
	            <select onchange="InsertUpdateCheck(this)" name="operation">
	            	<option hidden disabled selected value> -- select an option -- </option>
	            	<option value="Insert"> Insert New Record into Facility Table </option>
                    <option value="Update"> Update Existing Record in Facility Table </option>
	            </select>
	            </p>
	            <div id="Insert" style="display: none;">
	            </div>
	            <div id="Update" style="display: none;">
	            	<p>Facility ID: <input type="text" name="facid"/></p>
	            </div>
	            <div id="MakeVisible" style="display: none;">
	            	<p>Facility Street Address: <input type="text" name="facstreet" /></p>
	            	<p>Facility City: <input type="text" name="faccity" /></p>
	            	<p>Facility State: <input type="text" name="facstate" /></p>
	            	<p>Facility Zip Code: <input type="text" name="faczip" /></p>
	            	<p>Facility Size: <input type="number" placeholder="3000.00" step="0.01" name="facsize" /></p>
	                <p>Facility Type: 
	                <select onchange="FacTypeCheck(this)" name="factype">
	                	<option hidden disabled selected value> -- select an option -- </option>
	                    <option value="Office"> Office </option>
	                    <option value="OPS"> Outpatient Surgery </option>
	                </select>
	                </p>
	            	<br />
	            	<div id="Office" style="display: none;">
	            		<p>Office Count: <input type="number" placeholder="1" name="officecount" /></p>
	            	</div>
	            	<div id="OPS" style="display: none;">
	            		<p>Procedure Code: <input type="text" name="opsproccode"/></p>
	            		<p>Procedure Description: <input type="text" name="opsprocdesc"/></p>
	            		<p>Room Count: <input type="number" placeholder="1" name="opsroomcount"/></p>

	            	</div>
	            	<br />
	            	<br />
	            	<p><input type="reset" value="Clear Form" />&nbsp; 
	            	&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" 
	            	value="Send Form" /></p>
	            </div>
            </form>
        </div>
    </div>

</body>
<html>