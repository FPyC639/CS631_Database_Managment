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
<body>
<form name="employee" action="mcrud.php" method="post">
	<h1>MHS Employee Management</h1>
	<p><input type = "hidden" name = "table-name" value = "Employee" /></p>
	<p> Select an option from the dropdown: 
	<select onchange="InsertUpdateCheck(this)" name="operation">
		<option hidden disabled selected value> -- select an option -- </option>
		<option value="Insert"> Insert New Record into Employee Table </option>
        <option value="Update"> Update Existing Record in Employee Table </option>
	</select>
	</p>
	<div id="Insert" style="display: none;">
		<!-- <p>Employee SSN: <input type="text" name="empssn" /></p> -->
	</div>
	<div id="Update" style="display: none;">
		<p>Employee ID: <input type="text" name="empid"/></p>
		<!-- <p>Employee SSN: <input type="text" name="empssn" /></p> -->
	</div>
	<div id="MakeVisible" style="display: none;">
        <p>Employee SSN2: <input type="text" name="empssn" /></p>
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
			<p>HCP Job Title: <input type="text" name="emptitle" /></p>
		</div>
		<div id="MD" style="display: none;">
			<p>Doctor Specialty: <input type="text" name="empspec" /></p>
			<p>Doctor Board Certification Date: <input type="date" name="empbdcertdt"/></p>
		</div>
		<div id="Nurse" style="display: none;">
			<p>Nurse Certification: <input type="text" name="empcert"/></p>
		</div>
		<div id="Admin" style="display: none;">
			<p>Admin Job Title: <input type="text" name="emptitle"/></p>
		</div>
		<br />
		<br />
		<p><input type="reset" value="Clear Form" />&nbsp; 
		&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" 
		value="Send Form" /></p>
	</div>
</form>
</body>
</html>