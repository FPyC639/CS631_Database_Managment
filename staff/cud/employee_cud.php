<?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $empssn = null;
        $empfname = null;
        $empminit = null;
        $emplname = null;
        $empstreet = null;
        $empcity = null;
        $empstate = null;
        $empzip = null;
        $empsal = null;
        $emphiredt = null;
        $empfacid = null;
        $empjobclass = null;
        if ($_POST["empssn"] !== null) {
            $empssn = $_POST["empssn"];
        }
        if ($_POST["empfname"] !== null) {
            $empfname = $_POST["empfname"];
        }
        if ($_POST["empminit"] !== null) {
            $empminit = $_POST["empminit"];
        }
        if ($_POST["emplname"] !== null) {
            $emplname = $_POST["emplname"];
        }
        if ($_POST["empstreet"] !== null) {
            $empstreet = $_POST["empstreet"];
        }
        if ($_POST["empcity"] !== null) {
            $empcity = $_POST["empcity"];
        }
        if ($_POST["empstate"] !== null) {
            $empstate = $_POST["empstate"];
        }
        if ($_POST["empzip"] !== null) {
            $empzip = $_POST["empzip"];
        }
        if ($_POST["empsal"] !== null) {
            $empsal = $_POST["empsal"];
        }
        if ($_POST["empfac"] !== null) {
            $emphiredt = $_POST["empfac"];
        }
        if ($_POST["empfac"] !== null) {
            $empfacid = $_POST["empfac"];
        }
        if ($_POST["empjobclass"] !== null) {
            $empjobclass = $_POST["empjobclass"];
        }
        
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
            // First Insert into Employee
            $sql0 = "INSERT INTO Employee (SSN, FName, MInit, LName, Street, City, State, Zip_Code, Salary, Hire_Date, Job_Class, Facility_ID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
           
           $stmt0 = $conn->prepare($sql0);
            // Assuming all the parameters are strings, bind them as such.
            // If any of them are different data types (like integers or dates), you should bind them appropriately.
            $stmt0->bind_param("sssssssssssi", $empssn, $empfname, $empminit, $emplname, $empstreet, $empcity, 
                   $empstate, $empzip, $empsal, $emphiredt, $empjobclass, $empfacid);
            
            $stmt0->execute();
            echo "New Employee in Employee table has been successfully created";
            if ($empjobclass == "HCP") {
                $hcptitle = $_POST["emphcptitle"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $conn->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch_assoc();
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Other_HCP SET Job_Title = '$hcptitle' WHERE Employee_ID = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $latestEmployeeID);
                $stmt1->execute();
                echo "New Employee ID = '$latestEmployeeID' in Other HCP table has been successfully created and updated";
            } elseif ($empjobclass == "MD") {
                $docspecialty = $_POST["empspec"];
                $docbdcertdate = $_POST["empbdcertdt"];
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $conn->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch_assoc();
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Doctor SET Specialty = '$docspecialty', Board_Certification_Date = '$docbdcertdate' WHERE Employee_ID = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $latestEmployeeID);
                $stmt1->execute();
                echo "New Employee ID = '$latestEmployeeID' in Doctor table has been successfully created and updated";
            } elseif ($empjobclass == "Nurse") {
                $nursecertification = $_POST["empcert"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $conn->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch_assoc();
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Nurse SET Certification = '$nursecertification' WHERE Employee_ID = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $latestEmployeeID);
                $stmt1->execute();
                echo "New Employee ID = '$latestEmployeeID' in Nurse table has been successfully created and updated";
            } elseif ($empjobclass == "Admin") {
                $admtitle = $_POST["empadmtitle"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $conn->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch_assoc();
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Admin SET Job_Title = '$admtitle' WHERE Employee_ID = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $latestEmployeeID);
                $stmt1->execute();
                echo "New Employee ID = '$latestEmployeeID' in Admin table has been successfully created and updated";
            }

            $conn->close();
        }
        if($_POST["operation"] == $u){
            $employee_id = $_POST['empid'];

            $sql = "UPDATE Employee SET";

            if ($empssn !== null) {
                $sql .= " SSN = '$empssn'";
            }
            if ($empfname !== null) {
                if ($empssn !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "FName = '$empfname'";
            }
            if ($empminit !== null) {
                if ($empssn !== null || $empfname !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "MInit = '$empminit'";
            }
            if ($emplname !== null) {
                if ($empssn !== null || $empfname !== null || $empminit !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "LName = '$emplname'";
            }
            if ($empstreet !== null) {
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= " Street = '$empstreet'";
            }
            if ($empcity !== null) {
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "City = '$empcity'";
            }
            if ($empstate !== null) {
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                    $empcity !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "State = '$empstate'";
            }
            if ($empzip !== null) {
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                    $empcity !== null || $empstate !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Zip_Code = '$empzip'";
            }
            if ($empsal !== null ){
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                    $empcity !== null || $empstate !== null || $empzip !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Salary = '$empsal'";
            }
            if ($emphiredt !== null ){
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                    $empcity !== null || $empstate !== null || $empzip !== null || $empsal !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Hire_Date = '$emphiredt'";
            }
            if ($empfacid !== null ){
                if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                    $empcity !== null || $empstate !== null || $empzip !== null || $empsal !== null || $emphiredt !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Facility_ID = ?";
            }
            if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                $empcity !== null || $empstate !== null || $empzip !== null || $empsal !== null || $emphiredt !== null || $empfacid !== null) {
                $sql .= " WHERE Employee_ID = ?";
                $stmt_update_main = $conn->prepare($sql);
                if ($empfacid !== null) {
                    $stmt_update_main->bind_param("ii", $empfacid, $employee_id);
                }
                else {
                    $stmt_update_main->bind_param("i", $employee_id);
                }
                $stmt_update_main->execute();
                echo "Employee ID = '$employee_id' in has been successfully updated in Employee table";
            }
            
            if ($empjobclass == "HCP") {
                $hcptitle = $_POST["emphcptitle"];
                $sql_subclass = "UPDATE Other_HCP SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = ?";

                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("i", $employee_id);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch_assoc();
                    $old_empjobclass = $query_result['Job_Class'];
                    if ($old_empjobclass == $empjobclass) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Job Class, need to delete the old value first!
                        echo "The selected Job Class does not match the original record in the database! <br />";
                        echo "This employee was previously categorized as '$old_empjobclass', but has now been requested to be updated to '$empjobclass'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_empjobclass' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($hcptitle !== null) {
                    $sql_subclass .= "Job_Title = '$hcptitle'";
                    $stmt_subclass = $conn->prepare($sql_subclass);
                    $stmt_subclass->execute();
                    echo "Employee ID = '$employee_id' in has been successfully updated in Other HCP table";
                }
            }
            elseif ($empjobclass == "MD") {
                $docspecialty = $_POST["empspec"];
                $docbdcertdate = $_POST["empbdcertdt"];
                $sql_subclass = "UPDATE Doctor SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = ?";

                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("i", $employee_id);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch_assoc();
                    $old_empjobclass = $query_result['Job_Class'];
                    if ($old_empjobclass == $empjobclass) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Job Class, need to delete the old value first!
                        echo "The selected Job Class does not match the original record in the database! <br />";
                        echo "This employee was previously categorized as '$old_empjobclass', but has now been requested to be updated to '$empjobclass'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_empjobclass' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($docspecialty !== null) {
                    $sql_subclass .= "Specialty = '$docspecialty'";
                }
                if ($docbdcertdate !== null) {
                    if ($docspecialty !== null) {
                        $sql_subclass .= ", ";
                    }
                    else {
                        $sql_subclass .= "Board_Certification_Date = '$docbdcertdate'";
                    }
                }
                if ($docspecialty !== null || $docbdcertdate !== null) {
                    $stmt_subclass = $conn->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
            elseif ($empjobclass == "Nurse") {
                $nursecertification = $_POST["empcert"];
                $sql_subclass = "UPDATE Nurse SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = ?";

                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("i", $employee_id);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch_assoc();
                    $old_empjobclass = $query_result['Job_Class'];
                    if ($old_empjobclass == $empjobclass) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Job Class, need to delete the old value first!
                        echo "The selected Job Class does not match the original record in the database! <br />";
                        echo "This employee was previously categorized as '$old_empjobclass', but has now been requested to be updated to '$empjobclass'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_empjobclass' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($nursecertification !== null) {
                    $sql_subclass .= "Certification = '$nursecertification'";
                    $stmt_subclass = $conn->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
            elseif ($empjobclass == "Admin") {
                $admtitle = $_POST["empadmtitle"];
                $sql_subclass = "UPDATE Admin SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = ?";

                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("i", $employee_id);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch_assoc();
                    $old_empjobclass = $query_result['Job_Class'];
                    if ($old_empjobclass == $empjobclass) {
                        $sql_subclass .= " ";
                    } 
                    else {
                        // Value doesn't match old Job Class, need to delete the old value first!
                        echo "The selected Job Class does not match the original record in the database! <br />";
                        echo "This employee was previously categorized as '$old_empjobclass', but has now been requested to be updated to '$empjobclass'. <br />";
                        echo "This operation cannot be completed directly. Please delete this record from the subclass '$old_empjobclass' and create a new one in the correct table. <br />";
                        echo "Note: The subclass will not be updated.";
                        return null;
                    }
                }
                if ($admtitle !== null) {
                    $sql_subclass .= "Job_Title = '$admtitle'";
                    $stmt_subclass = $conn->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
        }
        if ($_POST["operation"] == $d) {
            $employee_id = $_POST["delempid"];

            $sql_delete_superclass = "DELETE FROM Employee WHERE Employee_ID = :emp_id_val";
            // Subclasses now deleted by trigger before delete from employee
            
            $stmt_del_superclass_rec = $conn->prepare($sql_delete_superclass);
            $stmt_del_superclass_rec->bind_param("i", $employee_id);
            $stmt_del_superclass_rec->execute();
            echo "Employee ID = '$employee_id' has been successfully deleted from Employee Table";
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" name="viewport" content="text/html; charset=UTF-8">
	<title>employee.html</title>
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
    function EmpJobClassCheck(that) {
    	if (that.value == "HCP") 
        {
            document.getElementById("HCP").style.display = "block";
            document.getElementById("MD").style.display = "none";
            document.getElementById("Nurse").style.display = "none";
            document.getElementById("Admin").style.display = "none";
        }
    	else if (that.value == "MD") 
        {
            document.getElementById("MD").style.display = "block";
            document.getElementById("HCP").style.display = "none";
            document.getElementById("Nurse").style.display = "none";
            document.getElementById("Admin").style.display = "none";
        }
    	else if (that.value == "Nurse") 
        {
            document.getElementById("Nurse").style.display = "block";
            document.getElementById("MD").style.display = "none";
            document.getElementById("HCP").style.display = "none";
            document.getElementById("Admin").style.display = "none";
        }
    	else if (that.value == "Admin") 
        {
            document.getElementById("Admin").style.display = "block";
            document.getElementById("MD").style.display = "none";
            document.getElementById("Nurse").style.display = "none";
            document.getElementById("HCP").style.display = "none";
        }
        else
        {
            document.getElementById("HCP").style.display = "none";
            document.getElementById("MD").style.display = "none";
            document.getElementById("Nurse").style.display = "none";
            document.getElementById("Admin").style.display = "none";
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
<body>
<form name="employee" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h1>MHS Employee Management</h1>
	<p><input type = "hidden" name = "table-name" value = "Employee" /></p>
	<p> Select an option from the dropdown: 
	<select onchange="InsertUpdateDeleteCheck(this)" name="operation">
		<option hidden disabled selected value> -- select an option -- </option>
		<option value="Insert"> Insert New Record into Employee Table </option>
        <option value="Update"> Update Existing Record in Employee Table </option>
        <option value="Delete"> Delete a Record from the Employee Table </option>
	</select>
	</p>
	<div id="Insert" style="display: none;">
	</div>
	<div id="Update" style="display: none;">
		<p>Employee ID: <input type="text" name="empid"/></p>
	</div>
    <div id="Delete" style="display: none;">
		<p>Employee ID: <input type="text" name="delempid"/></p>
	</div>
	<div id="MakeVisible1" style="display: none;">
        <p>Employee SSN: <input type="text" name="empssn" /></p>
		<p>Employee First Name: <input type="text" name="empfname" /></p>
		<p>Employee Middle Initial: <input type="text" name="empminit" /></p>
		<p>Employee Last Name: <input type="text" name="emplname" /></p>
		<p>Employee Street Address: <input type="text" name="empstreet" /></p>
		<p>Employee City: <input type="text" name="empcity" /></p>
		<p>Employee State: <input type="text" name="empstate" /></p>
		<p>Employee Zip Code: <input type="text" name="empzip" /></p>
		<p>Employee Salary: <input type="number" placeholder="50000.00" step="0.01" name="empsal" /></p>
		<p>Employee Hire Date: <input type="date" name="emphiredt" /></p>
		<p>Employee Facility ID: <input type="text" name="empfac" /></p>
	    <p>Employee Job Class: 
	    <select onchange="EmpJobClassCheck(this)" name="empjobclass">
	    	<option hidden disabled selected value> -- select an option -- </option>
	        <option value="HCP"> HCP </option>
	        <option value="MD"> Doctor </option>
	        <option value="Nurse"> Nurse </option>
	        <option value="Admin"> Admin </option>
	    </select>
	    </p>
		<br />
		<div id="HCP" style="display: none;">
			<p>HCP Job Title: <input type="text" name="emphcptitle" /></p>
		</div>
		<div id="MD" style="display: none;">
			<p>Doctor Specialty: <input type="text" name="empspec" /></p>
			<p>Doctor Board Certification Date: <input type="date" name="empbdcertdt"/></p>
		</div>
		<div id="Nurse" style="display: none;">
			<p>Nurse Certification: <input type="text" name="empcert"/></p>
		</div>
		<div id="Admin" style="display: none;">
			<p>Admin Job Title: <input type="text" name="empadmtitle"/></p>
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
</body>
</html>