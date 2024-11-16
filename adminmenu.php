<?php
// Include the connection file
include 'connection.php';

// Button to download database backup
if (isset($_POST['download_backup'])) {
    // Database name
    $database = 'lms2'; // Use your database name

    // Output file
    $backupFile = $database . '_' . date('Y-m-d_H-i-s') . '.sql';

    // Command to create a .sql file
    $command = "mysqldump --user=root --password= --host=localhost $database > $backupFile";

    // Execute the command
    system($command, $output);

    // Download the file
    if (file_exists($backupFile)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backupFile));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        readfile($backupFile);
        unlink($backupFile); // Delete the file from the server after download
        exit;
    } else {
        echo "Failed to create backup.";
    }
}

// Check if the rollback button is clicked
if (isset($_POST['rollback_database'])) {
  // Path to your latest backup file
  $database = 'lms2';
  $backupFile = 'DB/lms2.sql'; // Replace with the correct path

  // Ensure the backup file exists
  if (file_exists($backupFile)) {
      // Command to restore the database using the backup file
      $command = "mysql --user=root --password= --host=localhost $database < $backupFile";

      // Execute the command
      system($command, $output);

      // Check for errors
      if ($output === 0) {
          echo "Database rollback successful.";
      } else {
          echo "Database rollback failed.";
      }
  } else {
      echo "Backup file not found.";
  }
}
?>

<div class="col-md-2">
 	<div class="menu">
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation"><a href="showprofile.php">Profile</a></li>
            <li role="presentation"><a href="admininsert.php">Add Admin</a></li>
            <li role="presentation"><a href="borrower.php">Borrowers</a></li>
            <li role="presentation"><a href="mknotice.php">Make Notice</a></li>
            <li role="presentation"><a href="allnotices.php">Notices</a></li>
            <li role="presentation"><a href="insertbooks.php">Add Book</a></li>
            <li role="presentation"><a href="insertpdf.php">Add PDF Book</a></li>
            <li role="presentation"><a href="allbooks.php">All Books</a></li>
            <li role="presentation"><a href="allpdfbooks.php">All PDF Books</a></li>
            <li role="presentation"><a href="alladmins.php">All Admins</a></li>  
            <li role="presentation"><a href="allstudents.php">All Students</a></li>
            <li role="presentation"><a href="allteachers.php">All Teachers</a></li>            
          	<li role="presentation"><a href="logout.php">Logout</a></li>
            <li role="presentation"><form method="post">
        <button type="submit" name="download_backup">Download Database Backup</button>
      </form></li>
      <li>
      <form method="post">
        <button type="submit" name="rollback_database">Rollback Database</button>
    </form>
</li>
      </ul>
    </div>
</div>