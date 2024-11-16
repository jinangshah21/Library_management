<?php 
error_reporting(E_PARSE | E_ERROR);
session_start(); 
$username = $_SESSION['username'];

require 'newcon.php';

$bid = $_GET['bid'];

// Start a transaction
mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

try {
    // Delete the record for the book being returned
    $sql = mysqli_query($link, "DELETE FROM borrowers WHERE username = '$username' AND bid = '$bid'");

    if ($sql) {
        // Commit the transaction if deletion is successful
        mysqli_commit($link);
        echo 'Your Book return is requested. Please return the book to the Library</br>';
    } else {
        throw new Exception('Unexpected error: Your book return could not be processed. Please try again.');
    }
} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($link);
    echo $e->getMessage();
}

// Close the database connection
mysqli_close($link);
?>
<a href="profile.php">Go Back to Profile</a>
