<?php
session_start(); 
require 'newcon.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $Author = $_POST['Author'];
    $ISBN = $_POST['ISBN'];
    $Edition = $_POST['Edition'];
    $dept = $_POST['dept'];
    $aval = $_POST['availability'];

    // Start a transaction
    mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

    try {
        // Execute the insert query
        $query = mysqli_query($link, "INSERT INTO booklist (name, Author, ISBN, Edition, dept, availability) 
            VALUES ('$name', '$Author', '$ISBN', '$Edition', '$dept', '$aval')");

        if ($query) {
            // Commit the transaction if insertion is successful
            mysqli_commit($link);
            echo "Book Inserted";
        } else {
            // If insertion fails, throw an exception to trigger rollback
            throw new Exception("Unexpected Error: Book not inserted.");
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

<?php include('adminheader.php'); ?>
<div class="row">
    <?php include('adminmenu.php'); ?>
    <div class="col-md-9">
        <p class="welcome">Welcome &nbsp;&nbsp;<span style="color:#2484A4;"><?php echo $_SESSION['username']; ?></span></p>

        <form class="form-horizontal" role="form" action="insertbooks.php" method="POST">
            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2" for="Author">Author:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="Author" name="Author" placeholder="Enter Author" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ISBN">ISBN:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ISBN" name="ISBN" placeholder="Enter ISBN" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="Edition">Edition</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="Edition" name="Edition" placeholder="Enter Edition" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="dept">Department</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dept" name="dept" placeholder="Enter Department" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="availability">Availability</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="availability" name="availability" placeholder="Enter Availability" required>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default" name="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('footer.php'); ?>
