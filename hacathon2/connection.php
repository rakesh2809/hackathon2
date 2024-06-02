<?php
// Database connection settings
$host = 'localhost'; // Change this if your MySQL database is hosted on a different server
$dbname = 'ration_distribution_system'; // Change this to your actual database name
$username = 'root'; // Change this to your MySQL username
$password = ''; // Change this to your MySQL password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = $_POST["name"];
        $rationCardNumber = $_POST["rationCardNumber"];
        $otp = $_POST["otp"];
        
        // Prepare SQL statement to insert data into the delivery_records table
        $stmt = $pdo->prepare("INSERT INTO delivery_records (name, rationCardNumber, otp) VALUES (:name, :rationCardNumber, :otp)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':rationCardNumber', $rationCardNumber);
        $stmt->bindParam(':otp', $otp);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Prepare response message
        $response = array("success" => true, "message" => "Delivery information successfully recorded.");
    } else {
        // If the request method is not POST, return an error message
        $response = array("success" => false, "message" => "Invalid request method.");
    }
} catch (PDOException $e) {
    // If an error occurs, prepare error response
    $response = array("success" => false, "message" => "Database error: " . $e->getMessage());
}

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
?>
