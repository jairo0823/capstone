<?php
include 'db_config.php';

$senderId = $_GET['sender_id'];
$receiverId = $_GET['receiver_id'];

$query = "
    SELECT * FROM messages 
    WHERE (sender_id = $senderId AND receiver_id = $receiverId) 
    OR (sender_id = $receiverId AND receiver_id = $senderId)
    ORDER BY created_at ASC
";
$result = $conn->query($query);

$messages = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);
?>
