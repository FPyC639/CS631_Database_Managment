<?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $patfname = $_POST["patfname"];
        $patminit = $_POST["patminit"];
        $patlname = $_POST["patlname"];
        $patstreet = $_POST["patstreet"];
        $patcity = $_POST["patcity"];
        $patstate = $_POST["patstate"];
        $patzip = $_POST["patzip"];
        $patdocid = $_POST["patdocid"];
        $patinsid = $_POST["patinsid"];

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = ""; // Replace with your actual password
        $dbname = "MHS";
        $i = "Insert";
        $u = "Update";
        $d = "Delete";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if($_POST["operation"] == $i){
            $sql0 = "INSERT INTO Patient (FName, MInit, LName, Street, City, State, Zip_Code, Primary_Physician_ID) 
                    VALUES ('$patfname', '$patminit', '$patlname', '$patstreet', '$patcity', '$patstate', '$patzip', :doc_id)";
            $stmt0 = $pdo->prepare($sql0);
            $stmt0->bindParam(':doc_id', $patdocid, PDO::PARAM_INT);
            $stmt0->execute();

            // Also insert into Insured_By table if Insurer_ID was provided in the form
            if ($patinsid !== null) {
                // Retrieve the latest inserted Patient_ID from the Patient Table
                $sql_get_new_patid = "SELECT Patient_ID  FROM Patient ORDER BY Patient_ID DESC LIMIT 1";
                $stmt_new_patid = $pdo->prepare($sql_get_new_facid);
                $stmt_new_patid->execute();
                $latestRecord = $stmt_new_patid->fetch(PDO::FETCH_ASSOC);
                $latestPatientID = $latestRecord['Patient_ID'];
                $sql1 = "INSERT INTO Insured_By (Patient_ID, Insurer_ID) 
                        VALUES (:pat_id, :ins_id)";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':pat_id', $latestPatientID, PDO::PARAM_INT);
                $stmt1->bindParam(':ins_id', $patinsid, PDO::PARAM_INT);
                $stmt1->execute();
            }
            /*if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }*/

            $conn->close();
        }
        if($_POST["operation"] == $u){
            $patient_id = $_POST["patid"];

            $sql = "UPDATE Patient SET";

            if ($patstreet !== null) {
                $sql .= " Street = '$patstreet'";
            }
            if ($patcity !== null) {
                if ($patstreet !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "City = '$patcity'";
            }
            if ($patstate !== null) {
                if ($patstreet !== null || $patcity !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "State = '$patstate'";
            }
            if ($patzip !== null) {
                if ($patstreet !== null || $patcity !== null || $patstate !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Zip_Code = '$patzip'";
            }
            if ($patfname !== null) {
                if ($patstreet !== null || $patcity !== null || $patstate !== null || $patzip !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "FName = '$patfname'";
            }
            if ($patminit !== null) {
                if ($patstreet !== null || $patcity !== null || $patstate !== null || $patzip !== null || $patfname !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "MInit = '$patfname'";
            }
            if ($patlname !== null) {
                if ($patstreet !== null || $patcity !== null || $patstate !== null || $patzip !== null || $patname !== null || $patminit !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "LName = '$patlname'";
            }
            if ($patdocid !== null) {
                if ($patstreet !== null || $patcity !== null || $patstate !== null || $patzip !== null || $patname !== null || $patminit !== null || $patlname !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Primary_Physician_ID = :doc_id";
            }
            if ($patstreet !== null || $patcity !== null || $patstate !== null || $patzip !== null || $patname !== null || $patminit !== null || $patlname !== null || $patdocid !== null) {
                $sql .= " WHERE Patient_ID = :pat_id_val";
                $stmt_update_main = $pdo->prepare($sql);
                if ($patdocid !== null) {
                    $stmt_update_main->bindParam(':doc_id', $patdocid, PDO::PARAM_INT);
                }
                $stmt_update_main->bindParam(':pat_id_val', $patient_id, PDO::PARAM_INT);
                $stmt_update_main->execute();
            }
            if ($patinsid !== null) {
                $sql_update_insured_by = "UPDATE Insured_By SET Insurer_ID = :ins_id WHERE Patient_ID = :pat_id";
                $stmt_update_insured_by = $pdo->prepare($sql_update_insured_by);
                $stmt_update_insured_by->bindParam(':ins_id', $patinsid, PDO::PARAM_INT);
                $stmt_update_insured_by->bindParam(':pat_id', $patient_id, PDO::PARAM_INT);
                $stmt_update_insured_by->execute();
            }
        }
        if ($_POST["operation"] == $d) {
            $patient_id = $_POST["delpatid"];

            $sql_delete_main = "DELETE FROM Patient WHERE Patient_ID = :pat_id_val";
            $stmt_del_main_rec = $pdo->prepare($sql_delete_main);
            $stmt_del_main_rec->bindParam('pat_id_val', $patient_id, PDO::PARAM_INT);
            $stmt_del_main_rec->execute();
            echo "Patient ID = '$patient_id' has been successfully deleted from Patient Table";
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
            height: 120vh;
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
</head>
<script type="text/javascript">
    function InsertUpdateDeleteCheck(that) {
    	if (that.value == "Insert") 
        {
            document.getElementById("Insert").style.display = "block";
            document.getElementById("Update").style.display = "none";
            document.getElementById("Delete").style.display = "none";
            document.getElementById("MakeVisible1").style.display = "block";
            document.getElementById("MakeVisible2").style.display = "block";
        }
    	else if (that.value == "Update") 
        {
            document.getElementById("Update").style.display = "block";
            document.getElementById("Insert").style.display = "none";
            document.getElementById("Delete").style.display = "none";
            document.getElementById("MakeVisible1").style.display = "block";
            document.getElementById("MakeVisible2").style.display = "block";
        }
        else if (that.value == "Delete") 
        {
            document.getElementById("Update").style.display = "none";
            document.getElementById("Insert").style.display = "none";
            document.getElementById("Delete").style.display = "block";
            document.getElementById("MakeVisible1").style.display = "none";
            document.getElementById("MakeVisible2").style.display = "block";
        }
        else
        {
            document.getElementById("Insert").style.display = "none";
            document.getElementById("Update").style.display = "none";
            document.getElementById("Delete").style.display = "none";
            document.getElementById("MakeVisible1").style.display = "none";
            document.getElementById("MakeVisible2").style.display = "none";
        }
    }
</script>
<body>
<form name="patient" action="insert.php" method="post">
	<h1>MHS Patient Management</h1>
	<p><input type = "hidden" name = "table-name" value = "Patient" /></p>
	<p>Select an option from the dropdown: 
	<select onchange="InsertUpdateDeleteCheck(this)" name="operation">
		<option hidden disabled selected value> -- select an option -- </option>
		<option value="Insert"> Insert New Record into Patient Table </option>
        <option value="Update"> Update Existing Record in Patient Table </option>
        <option value="Delete"> Delete a Record from the Patient Table </option>
	</select>
	</p>
	<div id="Insert" style="display: none;">
	</div>
	<div id="Update" style="display: none;">
		<p>Patient ID: <input type="text" name="patid" /></p>
	</div>
    <div id="Delete" style="display: none;">
		<p>Patient ID: <input type="text" name="delpatid" /></p>
	</div>
	<div id="MakeVisible1" style="display: none;">
		<p>Patient First Name: <input type="text" name="patfname" /></p>
		<p>Patient Middle Initial: <input type="text" name="patminit" /></p>
		<p>Patient Last Name: <input type="text" name="patlname" /></p>
		<p>Patient Street Address: <input type="text" name="patstreet" /></p>
		<p>Patient City: <input type="text" name="patcity" /></p>
		<p>Patient State: <input type="text" name="patstate" /></p>
		<p>Patient Zip Code: <input type="text" name="patzip" /></p>
		<p>Patient Primary Physician ID: <input type="text" name="patdocid" /></p>
        <p>Patient Insurer ID: <input type="text" name="patinsid" /></p>		
	</div>
    <div id="MakeVisible2" style="display: none;">
        <br />
		<br />
		<p><input type="reset" value="Clear Form" />&nbsp; 
		&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" 
		value="Send Form" /></p>
    </div>
</form>
</body>
</html>
