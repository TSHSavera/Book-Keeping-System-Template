<?php
// Include the database connection file
include 'db.php';

// Check if the ID is set
if (isset($_GET['id'])) {
    // Prepare the SQL query
    $stmt = $conn->prepare('UPDATE booksdb SET archiveStatus = 1 WHERE id = ?');

    // Bind the ID to the query
    $stmt->bind_param('i', $_GET['id']);

    // Execute the query
    if ($stmt->execute()) {
        // Echo a JSON response
        echo json_encode(['status' => 'success']);
    } else {
        // Echo a JSON response
        echo json_encode(['status' => 'error']);
    }
} else {
    // Redirect to the view page
    header('Location: view.php');
}

?>