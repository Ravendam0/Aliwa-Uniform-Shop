<?php
include("codes/config.php");

// Handle the file upload
if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0){
    $fileTmpPath = $_FILES['productImage']['tmp_name'];
    $fileName = time() . '_' . basename($_FILES['productImage']['name']);
    $fileName = preg_replace('/\s+/', '_', $fileName); // replace spaces
    $uploadFileDir = './uploads/';
    $dest_path = $uploadFileDir . $fileName;

    if(move_uploaded_file($fileTmpPath, $dest_path)){
        $imageUrl = "uploads/" . $fileName;

        // Insert product into database
        $stmt = $conn->prepare("INSERT INTO uniforms (name, description, imageUrl, price, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $_POST['productName'], $_POST['productDescription'], $imageUrl, $_POST['productPrice'], $_POST['productCategory']);

        if($stmt->execute()){
           echo json_encode(['success' => true]);
        } else {
            echo "DB error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded or upload error.";
}
$conn->close();