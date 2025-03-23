<?php
include 'db_config.php';

$senderId = $_POST['sender_id'];
$receiverId = $_POST['receiver_id'];
$message = $_POST['message'];

$query = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES ($senderId, $receiverId, '$message', NOW())";
$conn->query($query);
?>
