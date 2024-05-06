<?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $empssn = $_POST["empssn"];
        $empfname = $_POST["empfname"];
        $empminit = $_POST["empminit"];
        $emplname = $_POST["emplname"];
        $empstreet = $_POST["empstreet"];
        $empcity = $_POST["empcity"];
        $empstate = $_POST["empstate"];
        $empzip = $_POST["empzip"];
        $empsal = $_POST["empsal"];
        $emphiredt = $_POST["emphiredt"];
        $empfacid = $_POST["empfac"];
        $empjobclass = $_POST["empjobclass"];

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
            $stmt0->bind_param("ssssssssdssd", $empssn, $empfname, $empminit, $emplname, $empstreet, $empcity, 
                   $empstate, $empzip, $empsal, $emphiredt, $empjobclass, $empfacid);
            
            $stmt0->execute();

            if ($empjobclass == "HCP") {
                $hcptitle = $_POST["emphcptitle"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $pdo->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch(PDO::FETCH_ASSOC);
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Other_HCP SET Job_Title = '$hcptitle' WHERE Employee_ID = :employeeID";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':employeeID', $latestEmployeeID, PDO::PARAM_INT);
                $stmt1->execute();
                
            } elseif ($empjobclass == "MD") {
                $docspecialty = $_POST["empspec"];
                $docbdcertdate = $_POST["empbdcertdt"];
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $pdo->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch(PDO::FETCH_ASSOC);
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Doctor SET Specialty = '$docspecialty', Board_Certification_Date = '$docbdcertdate' WHERE Employee_ID = :employeeID";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':employeeID', $latestEmployeeID, PDO::PARAM_INT);
                $stmt1->execute();
                
            } elseif ($empjobclass == "Nurse") {
                $nursecertification = $_POST["empcert"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $pdo->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch(PDO::FETCH_ASSOC);
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Doctor SET Certification = '$nursecertification' WHERE Employee_ID = :employeeID";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':employeeID', $latestEmployeeID, PDO::PARAM_INT);
                $stmt1->execute();
                
            } elseif ($empjobclass == "Admin") {
                $admtitle = $_POST["empadmtitle"];
                
                // Retrieve the latest inserted Employee_ID for this Job Class
                $sql_get_new_empid = "SELECT Employee_ID FROM Employee WHERE Job_Class = '$empjobclass' ORDER BY Employee_ID DESC LIMIT 1";
                $stmt_new_empid = $pdo->prepare($sql_get_new_empid);
                $stmt_new_emp->execute();
                $latestRecord = $stmt_new_empid->fetch(PDO::FETCH_ASSOC);
                $latestEmployeeID = $latestRecord['Employee_ID'];

                // Only need to perform an update on the subclass as the trigger after insertion in superclass should have already created the corresponding record in the subclass
                $sql1 = "UPDATE Other_HCP SET Job_Title = '$admtitle' WHERE Employee_ID = :employeeID";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':employeeID', $latestEmployeeID, PDO::PARAM_INT);
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
                $sql .= "Facility_ID = '$empfacid'";
            }
            if ($empssn !== null || $empfname !== null || $empminit !== null || $emplname !== null || $empstreet !== null || 
                $empcity !== null || $empstate !== null || $empzip !== null || $empsal !== null || $emphiredt !== null || $empfacid !== null) {
                $sql .= " WHERE Employee_ID = :employee_id_val";
                $stmt_update_main = $pdo->prepare($sql);
                $stmt_update_main->bindParam(':employee_id_val', $employee_id, PDO::PARAM_INT);
                $stmt_update_main->execute();
            }
            
            if ($empjobclass == "HCP") {
                $hcptitle = $_POST["emphcptitle"];
                $sql_subclass = "UPDATE Other_HCP SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = :emp_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':emp_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
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
                    $stmt_subclass = $pdo->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
            elseif ($empjobclass == "MD") {
                $docspecialty = $_POST["empspec"];
                $docbdcertdate = $_POST["empbdcertdt"];
                $sql_subclass = "UPDATE Doctor SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = :emp_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':emp_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
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
                    $stmt_subclass = $pdo->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
            elseif ($empjobclass == "Nurse") {
                $nursecertification = $_POST["empcert"];
                $sql_subclass = "UPDATE Nurse SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = :emp_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':emp_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
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
                    $stmt_subclass = $pdo->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
            elseif ($empjobclass == "Admin") {
                $admtitle = $_POST["empadmtitle"];
                $sql_subclass = "UPDATE Admin SET ";
                $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = :emp_id_value";

                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':emp_id_value', $facility_id, PDO::PARAM_INT);
                $stmt_check->execute();
                if ($stmt_check->rowCount() > 0) {
                    $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
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
                    $stmt_subclass = $pdo->prepare($sql_subclass);
                    $stmt_subclass->execute();
                }
            }
        }
        if ($_POST["operation"] == $d) {
            $employee_id = $_POST["delempid"];

            $sql_delete_superclass = "DELETE FROM Employee WHERE Employee_ID = :emp_id_val";
            // Subclasses now deleted as per cascade delete constraint
            /*$job_class = null;
            $sql_check = "SELECT Job_Class FROM Employee WHERE Employee_ID = :emp_id_value";

            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':emp_id_value', $employee_id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                $results_check = $stmt_check->fetchAll();
                $query_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
                $empjobclass = $query_result['Job_Class'];

                if ($empjobclass === null) {
                    echo "Employee ID does not have a Job Class listed! Aborting delete operation... ";
                    return null;
                }
            }
            else {
                echo "Employee ID provided was not found in the Employee Table! ";
                return null;
            }

            // Delete subclass record first, otherwise it will violate referential integrity
            if ($job_class == "HCP") {
                $sql_delete_subclass = "DELETE FROM Other_HCP WHERE Employee_ID = :emp_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('emp_id_val', $employee_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Employee ID = '$employee_id' has been successfully deleted from Other_HCP Table";
            }
            elseif ($job_class == "MD") {
                $sql_delete_subclass = "DELETE FROM Doctor WHERE Employee_ID = :emp_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('emp_id_val', $employee_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Employee ID = '$employee_id' has been successfully deleted from Doctor Table";
            }
            elseif ($job_class == "Nurse") {
                $sql_delete_subclass = "DELETE FROM Nurse WHERE Employee_ID = :emp_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('emp_id_val', $employee_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Employee ID = '$employee_id' has been successfully deleted from Nurse Table";
            }
            elseif ($job_class == "Admin") {
                $sql_delete_subclass = "DELETE FROM Admin WHERE Employee_ID = :emp_id_val";
                $stmt_del_subclass_rec = $pdo->prepare($sql_delete_subclass);
                $stmt_del_subclass_rec->bindParam('emp_id_val', $employee_id, PDO::PARAM_INT);
                $stmt_del_subclass_rec->execute();
                echo "Employee ID = '$employee_id' has been successfully deleted from Admin Table";
            }*/
            $stmt_del_superclass_rec = $pdo->prepare($sql_delete_superclass);
            $stmt_del_superclass_rec->bindParam('emp_id_val', $employee_id, PDO::PARAM_INT);
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