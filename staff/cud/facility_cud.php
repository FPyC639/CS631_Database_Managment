<?php
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
        $d = "Delete";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if($_POST["operation"] == $i){
            // First Insert into Facility
            $sql0 = "INSERT INTO Facility (Street, City, State, Zip_Code, Size, Facility_Type) 
                    VALUES ('$facstreet', '$faccity', '$facstate', '$faczip', '$facsize', 'Office')";
            $stmt0 = $pdo->prepare($sql0);
            $stmt0->execute();
            if ($factype == "Office") {
                $officecount = $_POST["officecount"];
                // Retrieve the latest inserted Facility_ID for this Facility_Type
                $sql_get_new_facid = "SELECT Facility_ID FROM Facility WHERE Facility_Type = 'Office' ORDER BY Facility_ID DESC LIMIT 1";
                $stmt_new_facid = $pdo->prepare($sql_get_new_facid);
                $stmt_new_facid->execute();
                $latestRecord = $stmt_new_facid->fetch(PDO::FETCH_ASSOC);
                $latestFacilityID = $latestRecord['Facility_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Office SET Office_Count = :officeCount WHERE Facility_ID = :facilityID";
                
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':officeCount', $officecount, PDO::PARAM_INT);
                $stmt1->bindParam(':facilityID', $latestFacilityID, PDO::PARAM_INT);
                $stmt1->execute();
                
            } elseif ($factype == "OPS") {
                $opsproccode = $_POST["opsproccode"];
                $opsprocdesc = $_POST["opsprocdesc"];
                $opsroomcount = $_POST["opsroomcount"];
                // Retrieve the latest inserted Facility_ID for this Facility_Type
                $sql_get_new_facid = "SELECT Facility_ID FROM Facility WHERE Facility_Type = 'OPS' ORDER BY Facility_ID DESC LIMIT 1";
                $stmt_new_facid = $pdo->prepare($sql_get_new_facid);
                $stmt_new_facid->execute();
                $latestRecord = $stmt_new_facid->fetch(PDO::FETCH_ASSOC);
                $latestFacilityID = $latestRecord['Facility_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Outpatient_Surgery SET Procedure_Code = :opsProcCode, Procedure_Description = '$opsprocdesc', Room_Count = :roomCount WHERE Facility_ID = :facilityID";
                
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':opsProcCode', $opsproccode, PDO::PARAM_INT);
                $stmt1->bindParam(':roomCount', $officecount, PDO::PARAM_INT);
                $stmt1->bindParam(':facilityID', $latestFacilityID, PDO::PARAM_INT);
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
            $facility_id = $_POST['facid'];

            $sql = "UPDATE Facility SET";

            if ($facstreet !== null) {
                $sql .= " Street = '$facstreet'";
            }
            if ($faccity !== null) {
                if ($facstreet !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "City = '$faccity'";
            }
            if ($facstate !== null) {
                if ($facstreet !== null || $faccity !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "State = '$facstate'";
            }
            if ($faczip !== null) {
                if ($facstreet !== null || $faccity !== null || $facstate !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Zip_Code = '$faczip'";
            }
            if ($facsize !== null ){
                if ($facstreet !== null || $faccity !== null || $facstate !== null || $faczip !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Size = '$facsize'";
            }
            if ($facstreet !== null || $faccity !== null || $facstate !== null || $faczip !== null) {
                $sql .= " WHERE Facility_ID = :facility_id_val";
                $stmt_update_main = $pdo->prepare($sql);
                $stmt_update_main->bindParam(':facility_id_val', $facility_id, PDO::PARAM_INT);
                $stmt_update_main->execute();
            }
            
            if ($factype == "Office") {
                $officecount = $_POST["officecount"];
                $sql_subclass = "UPDATE Office SET ";
                $sql_check = "SELECT Facility_Type FROM Facility WHERE Facility_ID = :fac_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':fac_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $results_check = $stmt_check->fetchAll();
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
                    $old_factype = $query_result['Facility_Type'];
                    if ($old_factype == $factype) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Facility Type, need to delete the old value first!
                        echo "The selected Facility Type does not match the original record in the database! <br />";
                        echo "This facility was previously categorized as '$old_factype', but has now been requested to be updated to '$factype'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_factype' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($officecount !== null) {
                    $sql_subclass .= "Office_Count = :office_count_val";
                    $stmt_subclass = $pdo->prepare($sql_subclass);
                    $stmt_subclass->bindParam(':office_count_val', $officecount, PDO::PARAM_INT);
                    $stmt_subclass->execute();

                }
                
            }
            elseif ($factype == "OPS") {
                $opsproccode = $_POST["opsproccode"];
                $opsprocdesc = $_POST["opsprocdesc"];
                $opsroomcount = $_POST["opsroomcount"];
                $sql_subclass = "UPDATE Outpatient_Surgery SET ";
                $sql_check = "SELECT Facility_Type FROM Facility WHERE Facility_ID = :fac_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':fac_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $results_check = $stmt_check->fetchAll();
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
                    $old_factype = $query_result['Facility_Type'];
                    if ($old_factype == $factype) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Facility Type, need to delete the old value first!
                        echo "The selected Facility Type does not match the original record in the database! <br />";
                        echo "This facility was previously categorized as '$old_factype', but has now been requested to be updated to '$factype'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_factype' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($opsproccode !== null) {
                    $sql_subclass .= "Procedure_Code = :ops_proccode_val";
                }
                if ($opsprocdesc !== null) {
                    if ($opsproccode !== null) {
                        $sql_subclass .= ", ";
                    }
                    else {
                        $sql_subclass .= " ";
                    }
                    $sql_subclass .= "Procedure_Description = '$opsprocdesc'";
                }
                if ($opsroomcount !== null) {
                    $sql_subclass .= "Office_Count = :ops_roomcount_val";
                }
                if ($opsproccode !== null || $opsprocdesc !== null || $opsroomcount !== null) {
                    $stmt_subclass = pdo->prepare($sql_subclass);
                    if ($opsproccode !== null){
                        $stmt_subclass->bindParam(':ops_proccode_val', $opsproccode, PDO::PARAM_INT);
                    }
                    if ($opsroomcount !== null) {
                        $stmt_subclass->bindParam(':ops_roomcount_val', $opsroomcount, PDO::PARAM_INT);
                    }
                    $stmt_subclass->execute();
                }
            }
        }
        if ($_POST["operation"] == $d) {
            $facility_id = $_POST["delfacid"];
            $sql_delete_superclass = "DELETE FROM Facility WHERE Facility_ID = :fac_id_val";
            // Subclasses now deleted as per cascade delete constraint
            /*if ($facility_type == "Office") {
                $sql_delete_subclass = "DELETE FROM Office WHERE Facility_ID = :fac_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('fac_id_val', $facility_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Facility ID = '$facility_id' has been successfully deleted from Office Table";
            }
            elseif ($facility_type == "OPS") {
                $sql_delete_subclass = "DELETE FROM Outpatient_Surgery WHERE Facility_ID = :fac_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('fac_id_val', $facility_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Facility ID = '$facility_id' has been successfully deleted from Outpatient_Surgery Table";
            }*/
            $stmt_del_superclass_rec = $pdo->prepare($sql_delete_superclass);
            $stmt_del_superclass_rec->bindParam('fac_id_val', $facility_id, PDO::PARAM_INT);
            $stmt_del_superclass_rec->execute();
            echo "Facility ID = '$facility_id' has been successfully deleted from Facility Table";
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
	            <select onchange="InsertUpdateDeleteCheck(this)" name="operation">
	            	<option hidden disabled selected value> -- select an option -- </option>
	            	<option value="Insert"> Insert New Record into Facility Table </option>
                    <option value="Update"> Update Existing Record in Facility Table </option>
                    <option value="Delete"> Delete a Record from the Facility Table </option>
	            </select>
	            </p>
	            <div id="Insert" style="display: none;">
	            </div>
	            <div id="Update" style="display: none;">
	            	<p>Facility ID: <input type="text" name="facid"/></p>
	            </div>
                <div id="Delete" style="display: none;">
	            	<p>Facility ID: <input type="text" name="delfacid"/></p>
	            </div>
	            <div id="MakeVisible1" style="display: none;">
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
	            </div>
                <div id="MakeVisible2" style="display: none;">
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