<?php
// Database connection settings
$host = 'localhost'; // Assuming XAMPP is running on the same machine
$dbname = 'ration_management_system'; // Change this to your actual database name
$username = 'root'; // Default username for XAMPP MySQL
$password = ''; // Default password for XAMPP MySQL is empty

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

        // Check if the OTP and ration card number are valid in the database
        $stmt = $pdo->prepare("SELECT * FROM delivery_records WHERE rationCardNumber = :rationCardNumber AND otp = :otp");
        $stmt->bindParam(':rationCardNumber', $rationCardNumber);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            // If OTP and ration card number match, return success message
            $response = array("success" => true, "message" => "Delivery verified and successful for $name.");
        } else {
            // If OTP or ration card number doesn't match, return error message
            $response = array("success" => false, "message" => "Incorrect OTP or Ration Card Number. Please try again.");
        }
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
