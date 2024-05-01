<?php
// Start the session
session_start();

// MySQL database connection parameters
$hostname = "localhost"; // Change this to your MySQL server hostname
$username_db = "root"; // Change this to your MySQL username
$password_db = ""; // Change this to your MySQL password
$database_name = "Health"; // Change this to your MySQL database name

try {
    // Connect to MySQL database using PDO
    $dsn = "mysql:host=$hostname;dbname=$database_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $conn = new PDO($dsn, $username_db, $password_db, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST["empid"];
    $password = $_POST["emppwd"];

    // Prepare SQL statement
    $sql = "SELECT username FROM users WHERE username = :username AND password = :password";

    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    // Execute SQL statement
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        // Set session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;

        // Set a session cookie that expires when the browser is closed
        setcookie("user", $username, 0, "/");

        // Redirect to another page
        header("Location: welcome.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}

// Close the MySQL connection
$conn = null;
?>
