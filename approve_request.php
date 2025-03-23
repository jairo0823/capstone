<?php
// approve_request.php
include 'db_config.php'; // Include DB connection

if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        
        // Update the status in the renewal_requests table
        $sql = "UPDATE renewal_requests SET status = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $request_id);

        if ($stmt->execute()) {
            echo "Renewal request has been " . $status . ".";
        } else {
            echo "Error updating request: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid action.";
    }

    $conn->close();
} else {
    echo "No request ID or action specified.";
}
?>
