<?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $inscompname = null;
        $inscompstreet = null;
        $inscompcity = null;
        $inscompstate = null;
        $inscompzip = null;
        if ($_POST["inscompname"] !== null) {
            $inscompname = $_POST["inscompname"];
        }
        if ($_POST["inscompstreet"] !== null) {
            $inscompstreet = $_POST["inscompstreet"];
        }
        if ($_POST["inscompcity"] !== null) {
            $inscompcity = $_POST["inscompcity"];
        }
        if ($_POST["inscompstate"] !== null) {
            $inscompstate = $_POST["inscompstate"];
        }
        if ($_POST["inscompzip"] !== null) {
            $inscompzip = $_POST["inscompzip"];
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
            $sql0 = "INSERT INTO Insurance_Company (Name, Street, City, State, Zip_Code) 
                    VALUES ('$inscompname', '$inscompstreet', '$inscompcity', '$inscompstate', '$inscompzip')";
            $stmt0 = $conn->prepare($sql0);
            $stmt0->execute();
            echo "New Insurance Company has been successfully inserted into Insurance Company Table";
            /*if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }*/

            $conn->close();
        }
        if($_POST["operation"] == $u){
            $insurer_id = $_POST["insid"];

            $sql = "UPDATE Insurance_Company SET";

            if ($inscompstreet !== null) {
                $sql .= " Street = '$inscompstreet'";
            }
            if ($incompcity !== null) {
                if ($inscompstreet !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "City = '$inscompcity'";
            }
            if ($inscompstate !== null) {
                if ($inscompstreet !== null || $inscompcity !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "State = '$inscompstate'";
            }
            if ($inscompzip !== null) {
                if ($inscompstreet !== null || $inscompcity !== null || $inscompstate !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Zip_Code = '$inscompzip'";
            }
            if ($inscompname !== null) {
                if ($inscompstreet !== null || $inscompcity !== null || $inscompstate !== null || $inscompzip !== null) {
                    $sql .= ", ";
                }
                else {
                    $sql .= " ";
                }
                $sql .= "Name = '$inscompname'";
            }
            
            if ($inscompstreet !== null || $inscompcity !== null || $inscompstate !== null || $inscompzip !== null || $inscompname !== null) {
                $sql .= " WHERE Insurer_ID = ?";
                $stmt_update_main = $conn->prepare($sql);
                $stmt_update_main->bind_param("i", $insurer_id);
                $stmt_update_main->execute();
                echo "Insurer ID = '$insurer_id' has been successfully updated in Insurance Company Table";
            }
        }
        if ($_POST["operation"] == $d) {
            $insurer_id = $_POST["delinsid"];

            $sql_delete_main = "DELETE FROM Facility WHERE Insurer_ID = ?";
            $stmt_del_main_rec = $conn->prepare($sql_delete_main);
            $stmt_del_main_rec->bind_param("i", $insurer_id);
            $stmt_del_main_rec->execute();
            echo "Insurer ID = '$insurer_id' has been successfully deleted from Insurance Company Table";
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" name="viewport" content="text/html; charset=UTF-8">
	<title>insurance_company.html</title>
	<style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
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
<form name="insurance_company" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h1>MHS Insurance Company Module</h1>
	<input type = "hidden" name = "table-name" value = "Insurance_Company" />
	<p>Select an option from the dropdown:
	<select onchange="InsertUpdateDeleteCheck(this)" name="operation">
		<option hidden disabled selected value> -- select an option -- </option>
		<option value="Insert"> Insert New Record into Insurance Company Table </option>
        <option value="Update"> Update Existing Record in Insurance Company Table </option>
        <option value="Delete"> Delete a Record from the Insurance Company Table </option>
	</select>
	</p>
	<div id="Insert" style="display: none;">
	</div>
	<div id="Update" style="display: none;">
		<p>Insurer ID: <input type="text" name="insid" /></p>
	</div>
    <div id="Delete" style="display: none;">
		<p>Insurer ID: <input type="text" name="delinsid" /></p>
	</div>
	<div id="MakeVisible1" style="display: none;">
		<p>Company Name: <input type="text" name="inscompname" /></p>
		<p>Company Street Address: <input type="text" name="inscompstreet" /></p>
		<p>Company City: <input type="text" name="inscompcity" /></p>
		<p>Company State: <input type="text" name="inscompstate" /></p>
		<p>Company Zip Code: <input type="text" name="inscompzip" /></p>
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
