<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["proof_image"])) {
    $order_id = $_POST['order_id'];
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/inti/gearUp/assets/";
    $file_name = basename($_FILES["proof_image"]["name"]);
    $target_file = $target_dir . $file_name;
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow only image files
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($image_file_type, $allowed_types)) {
        die("Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)) {
        // Store file path in database
        $sql = "UPDATE orders SET sent_proof_image = '$file_name' WHERE order_id = '$order_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: order.php?message2=Proof uploaded successfully");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Error uploading the file.";
    }
}
?>