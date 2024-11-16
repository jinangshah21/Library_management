<?php 
require 'newcon.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start a transaction
    mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

    try {
        // Execute the delete query
        $sql = mysqli_query($link, "DELETE FROM admin_data WHERE id = '$id'");

        if ($sql) {
            // Commit the transaction if deletion is successful
            mysqli_commit($link);
            echo 'Record deleted!';
        } else {
            // If deletion fails, throw an exception to trigger rollback
            throw new Exception("Unexpected Error: Record didn't delete.");
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        mysqli_rollback($link);
        echo $e->getMessage();
    }

    // Close the database connection
    mysqli_close($link);
}
?>
<a href="adminarea.php">Go Admin area</a>
