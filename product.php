<?php
include("codes/config.php");
header('Content-Type: application/json');

$sql = "SELECT id, name, description, imageUrl, price, category, created_at FROM uniforms ORDER BY id DESC";
$result = $conn->query($sql);

$products = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>
