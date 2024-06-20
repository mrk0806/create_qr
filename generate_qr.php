<?php
// Include library QR code
include 'phpqrcode/qrlib.php';

// Function to generate QR code
function generateQRCode($data) {
    // Directory to store generated QR code images
    $dir = 'qr_codes/';

    // Ensure the directory exists or create it
    if (!file_exists($dir)) {
        mkdir($dir);
    }

    // QR code filename
    $file = $dir . uniqid() . '.png';

    // Generate QR code
    QRcode::png($data, $file);

    // Return QR code filename
    return $file;
}

// Function to save QR code information to database
function saveQRCodeToDatabase($qrText, $qrFilename) {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "latihan");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO qr_codes (qr_text, qr_filename) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $qrText, $qrFilename);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "QR code information saved to database.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Check if QR data is set in POST request
if(isset($_POST['qrData'])) {
    // Get QR data from POST request
    $qrData = $_POST['qrData'];

    // Generate QR code
    $qrCodeFile = generateQRCode($qrData);

    // Save QR code information to database
    saveQRCodeToDatabase($qrData, $qrCodeFile);

    // Output QR code image tag
    echo '<img src="' . $qrCodeFile . '" alt="QR Code">';
}
?>
