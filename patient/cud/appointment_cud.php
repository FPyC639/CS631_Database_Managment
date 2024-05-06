<?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data

        $patid = null;
        $docid = null;
        $facid = null;
        $apptdatetime = null;
        if ($_POST["patid"] !== null) {
            $patid = $_POST["patid"];
        }
        if ($_POST["docid"] !== null) {
            $docid = $_POST["docid"];
        }
        if ($_POST["facid"] !== null) {
            $facid = $_POST["facid"];
        }
        if ($_POST["apptdatetime"] !== null) {
            $apptdatetime = $_POST["apptdatetime"];
        }
        if ($patid === null || $docid === null || $facid === null || $apptdatetime === null){
            echo "Missing required information for Appointment: <br />";
            if ($patid === null) {
                echo "* Patient ID <br />";
            }
            if ($docid === null) {
                echo "* Physician ID <br />";
            }
            if ($facid === null) {
                echo "* Facility ID <br />";
            }
            if ($apptdatetime === null) {
                echo "* Appointment Date/Time <br />";
            }
            echo "Please enter all required information and try again!";
            return null;
        }
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
            $apptdesc = $_POST["apptdesc"];

            $sql0 = "INSERT INTO Invoice_Detail (Patient_ID, Physician_ID, Facility_ID, Date_Time, Description) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt0 = $conn->prepare($sql0);
            $datetime_obj = new DateTime($apptdatetime);
            $fin_appt_datetime = $datetime_obj->format('Y-m-d H:i:s');
            $stmt0->bind_param("iiiss", $patid, $docid, $facid, $fin_appt_datetime, $apptdesc);
            $stmt0->execute();

            echo "Appointment for Patient ID = '$patid' with Physician ID = '$docid' at Facility ID = '$facid' on/at '$fin_appt_datetime' has been successfully created. <br />";
            /*if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }*/

            $conn->close();
        }
        if($_POST["operation"] == $u){
            $operation_type = $_POST["operation-type"]
            $cost_update = "Cost_Update";
            $detail_update = "Detail_Update"
            
            $new_patid = $_POST["newpatid"];
            $new_docid = $_POST["newdocid"];
            $new_facid = $_POST["newfacid"];
            $new_apptdatetime = $_POST["newapptdatetime"];
            $new_apptdesc = $_POST["newapptdesc"];
            $apptcost = $_POST["apptcost"]; 

            $sql = "UPDATE Invoice_Detail SET";
            if ($operation_type == $cost_update) {
                if ($apptcost !== null) {
                    $sql .= " Cost = ? WHERE Patient_ID = ?, Physician_ID = ?, Facility_ID = ?, Date_Time = ?";
                    $stmt_update_cost = $conn->prepare($sql);
                    $datetime_obj = new DateTime($apptdatetime);
                    $fin_appt_datetime = $datetime_obj->format('Y-m-d H:i:s');
                    $stmt_update_cost->bind_param("siiis", $apptcost, $patid, $docid, $facid, $fin_appt_datetime);
                    $stmt_update_cost->execute();
                    echo "Cost of appointment for Patient ID = '$patid' with Physician ID = '$docid' at Facility ID = '$facid' on/at '$fin_appt_datetime' has been successfully updated. <br />";
                    echo "An invoice for this appointment has been generated. <br />";
                }
                else {
                    echo "Appointment Cost was not captured correctly. Aborting update operation... Please try again!";
                    return null;
                }
            }
            elseif ($operation_type == $detail_update) {
                if ($new_patid !== null) {
                    $sql .= " Patient_ID = ?";
                }
                if ($new_docid !== null) {
                    if ($new_patid !== null) {
                        $sql .= ", ";
                    }
                    else {
                        $sql .= " ";
                    }
                    $sql .= " Physician_ID = ?";
                }
                if ($new_facid !== null) {
                    if ($new_patid !== null || $new_docid !== null) {
                        $sql .= ", ";
                    }
                    else {
                        $sql .= " ";
                    }
                    $sql .= " Facility_ID = ?";
                }
                if ($new_apptdatetime !== null) {
                    if ($new_patid !== null || $new_docid !== null || $new_facid !== null) {
                        $sql .= ", ";
                    }
                    else {
                        $sql .= " ";
                    }
                    $sql .= " Date_Time = ?";
                }
                if ($new_apptdesc !== null) {
                    if ($new_patid !== null || $new_docid !== null || $new_facid !== null || $new_apptdatetime !== null) {
                        $sql .= ", ";
                    }
                    else {
                        $sql .= " ";
                    }
                    $sql .= " Description = '$new_apptdesc'";
                }
                if ($new_patid !== null || $new_docid !== null || $new_facid !== null || $new_apptdatetime !== null || $new_apptdesc !== null) {
                    $sql .= " WHERE Patient_ID = ?, Physician_ID = ?, Facility_ID = ?, Date_Time = ?";
                    $datetime_obj = new DateTime($apptdatetime);
                    $fin_appt_datetime = $datetime_obj->format('Y-m-d H:i:s');
                    $stmt_update_detail = $conn->prepare($sql);
                    if ($new_patid !== null && $new_docid !== null && $new_facid !== null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("iiisiiis", $new_patid, $new_docid, $new_facid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid !== null && $new_facid !== null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("iisiiis", $new_docid, $new_facid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid !== null && $new_facid === null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("isiiis", $new_docid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid !== null && $new_facid !== null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiiis", $new_docid, $new_facid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid !== null && $new_facid === null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiis", $new_docid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid === null && $new_facid !== null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiis", $new_facid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid === null && $new_facid === null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("siiis", $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid !== null && $new_facid !== null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiiiis", $new_patid, $new_docid, $new_facid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid !== null && $new_facid === null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiiis", $new_patid, $new_docid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid === null && $new_facid === null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiis", $new_patid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid === null && $new_facid !== null && $new_apptdatetime === null) {
                        $stmt_update_detail->bind_param("iiiiis", $new_patid, $new_facid, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid === null && $new_facid === null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("isiiis", $new_patid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid === null && $new_facid !== null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("iisiiis", $new_patid, $new_facid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid !== null && $new_docid !== null && $new_facid === null && $new_apptdatetime !== null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("iisiiis", $new_patid, $new_docid, $fin_appt_datetime1, $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    elseif ($new_patid === null && $new_docid === null && $new_facid === null && $new_apptdatetime === null) {
                        $datetime_obj1 = new DateTime($new_apptdatetime);
                        $fin_appt_datetime1 = $datetime_obj1->format('Y-m-d H:i:s');
                        $stmt_update_detail->bind_param("iiis", $patid, $docid, $facid, $fin_appt_datetime);
                    }
                    $stmt_update_detail->execute();
                    echo "Appointment originally for Patient ID = '$patid' with Physician ID = '$docid' at Facility ID = '$facid' on/at '$fin_appt_datetime' has been successfully updated.";
                }
            }
        }
        if ($_POST["operation"] == $d) {
            $sql_delete_main = "DELETE FROM Invoice_Detail WHERE Patient_ID = ?, Physician_ID = ?, Facility_ID = ?, Date_Time = ?";
            $stmt_del_main_rec = $conn->prepare($sql_delete_main);
            $datetime_obj = new DateTime($apptdatetime);
            $fin_appt_datetime = $datetime_obj->format('Y-m-d H:i:s');
            $stmt_del_main_rec->bind_param("iiis", $patid, $docid, $facid, $fin_appt_datetime);
            $stmt_del_main_rec->execute();
            echo "Appointment for Patient ID = '$patid' with Physician ID = '$docid' at Facility ID = '$facid' on/at '$fin_appt_datetime' has been successfully deleted.";
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" name="viewport" content="text/html; charset=UTF-8">
	<title>appointment.html</title>
	<style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 110vh;
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
            document.getElementById("MakeVisible1").style.display = "none";
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
    function UpdateCheckTypes(that) {
    	if (that.value == "Cost_Update")
        {
            document.getElementById("Cost_Update").style.display = "block";
            document.getElementById("Detail_Update").style.display = "none";
        }
    	else if (that.value == "Detail_Update") 
        {
            document.getElementById("Detail_Update").style.display = "block";
            document.getElementById("Cost_Update").style.display = "none";
        }
        else
        {
            document.getElementById("Cost_Update").style.display = "none";
            document.getElementById("Detail_Update").style.display = "none";
        }
    }
</script>
<body>
<form name="appointment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h1>MHS Appointment Management</h1>
	<p><input type = "hidden" name = "table-name" value = "Invoice_Detail" /></p>
	<p>Select an option from the dropdown:&nbsp;&nbsp;
	<select onchange="InsertUpdateDeleteCheck(this)" name="operation">
		<option hidden disabled selected value> -- select an option -- </option>
		<option value="Insert"> Create New Appointment </option>
        <option value="Update"> Update Existing Appointment </option>
        <option value="Delete"> Delete an Appointment </option>
	</select>
	</p>
    <p>Patient ID:&nbsp;&nbsp; <input type="text" name="patid" /></p>
    <p>Physician ID:&nbsp;&nbsp; <input type="text" name="docid" /></p>
    <p>Facility ID:&nbsp;&nbsp; <input type="text" name="facid" /></p>
    <p>Appointment Date and Time:&nbsp;&nbsp; <input type="datetime-local" name="apptdatetime" /></p>
	<div id="Insert" style="display: none;">
		<p>Description:&nbsp;&nbsp; <input type="text" name="apptdesc" /></p>
	</div>
	<div id="Update" style="display: none;">
		<!-- Section to insert new record into Invoice Table upon update of appointment -->
		<p>Select an option from the dropdown:&nbsp;&nbsp;
		<select onchange="UpdateCheckTypes(this)" name="operation-type">
			<option hidden disabled selected value> -- select an option -- </option>
			<option value="Cost_Update"> Update Cost on Appointment (Generate Invoice) </option>
	        <option value="Detail_Update"> Update Existing Appointment (Basic Details Only) </option>
		</select>
		</p>
        <div id="MakeVisible1" style="display: none;">
            <p>** Note: Enter data in "New" Fields only if you want to update those fields **</p>
			<br />
        </div>
		<div id="Detail_Update" style="display: none;">
			<p>New Patient ID (if applicable): <input type="text" name="newpatid" /></p>
			<p>New Physician ID (if applicable): <input type="text" name="newdocid" /></p>
			<p>New Facility ID (if applicable):&nbsp;&nbsp; <input type="text" name="newfacid" /></p>
			<p>New Appointment Date and Time (if applicable):&nbsp;&nbsp; <input type="datetime-local"
					name="newapptdatetime" /></p>
			<p>New Description (if applicable):&nbsp;&nbsp; <input type="text" name="newapptdesc" /></p>
		</div>
		<div id="Cost_Update" style="display: none;">
			<p>Cost: <input type="number" placeholder="100.00" step="0.01" name="apptcost" /></p>
		</div>
	</div>
    <div id="Delete" style="display: none;">
    </div>
    <div id="MakeVisible2" style="display: none;">
        <br />
        <br />
        <p><input type="reset" value="Clear Form" />&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit"
			value="Send Form" /></p>
		<br />
		<br />
    </div>
</form>
</body>
</html>
