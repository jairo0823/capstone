<?php
include 'db_config.php';

$query = "SELECT id, firstname, lastname FROM tenants";
$result = $conn->query($query);

$tenants = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tenants[] = $row;
    }
}

echo json_encode($tenants);
?>
