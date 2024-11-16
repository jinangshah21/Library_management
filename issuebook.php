<?php 
error_reporting(E_PARSE | E_ERROR);
session_start(); 
$username = $_SESSION['username'];
$bid = $_GET['id'];

require 'newcon.php';

$idnew = $_GET['id'];
$currentdate = date("Y-m-d");

$select = mysqli_query($link, "SELECT * FROM booklist WHERE id = '$idnew'");
$row = mysqli_fetch_array($select);
$isbn = $row['ISBN'];

// Start a transaction
mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

try {
    // Insert into borrowers table
    $sql = mysqli_query($link, "INSERT INTO borrowers (username, date_of_taken, isbn, bid)
        VALUES ('$username', '$currentdate', '$isbn', '$bid')");

    // Insert into bookhistory table
    $cmd = mysqli_query($link, "INSERT INTO bookhistory (username, date_of_taken, isbn, bid)
        VALUES ('$username', '$currentdate', '$isbn', '$bid')");

    if ($sql && $cmd) {
        // Decrement availability in booklist table
        $updateAvailability = mysqli_query($link, "UPDATE booklist SET availability = availability - 1 WHERE id = '$idnew'");

        if ($updateAvailability) {
            // Commit the transaction if the update is successful
            mysqli_commit($link);
            echo 'Your Book is booked. Please collect from Library</br>';
        } else {
            // If the update fails, throw an exception
            throw new Exception("Failed to update book availability! Try Again!</br>");
        }
    } else {
        // If either insert fails, throw an exception
        throw new Exception("Unexpected error: Your Book is not booked! Try Again!</br>");
    }
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    mysqli_rollback($link);
    echo $e->getMessage();
}

// Close the database connection
mysqli_close($link);
?>
<a href="profile.php">Go Back</a>
