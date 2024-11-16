<?php 
session_start();
$dept = '';

if (isset($_POST['dept'])) {
    $dept = $_POST['dept'];
    //echo $dept;
}

include('adminheader.php');
?>

<div class="row">
    <?php include('stmenu.php'); ?>
    
    <div class="col-md-10">
        <p class="welcome">Welcome <span style="color:#2484A4;"><?php echo $_SESSION['username']; ?></span></p>
        <?php $username = $_SESSION['username']; ?>

        <form class="form-horizontal" role="form" action="bookrequest.php" method="POST">          
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="dept">Select Dept</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="dept" name="dept">
                            <option value="select">Select Your Department</option>
                            <option value="CSE">Computer Science and Engineering</option>
                            <option value="EEE">Electronics and Electrical Engineering</option>
                            <option value="Civil">Civil Engineering</option>
                            <option value="Mechanical">Mechanical Engineering</option>
                            <option value="Textile Eng">Textile Engineering</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default" name="submit">Search Book</button>
                    </div>
                </div>
            </div>
        </form> 

        <div class="row">
            <div class="col-md-10">
                <h1>Available Books</h1>
                <table cellpadding="2" cellspacing="2" border="2" class="table table-bordered">
                    <tr>
                        <th>Name</th>    
                        <th>Author</th>
                        <th>Edition</th>                           
                        <th>Availability</th>                           
                        <th>Option</th>
                    </tr>
                    <?php
                    require 'newcon.php';  // include connection

                    // Start a transaction
                    mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

                    try {
                        $data = mysqli_query($link, "SELECT id, name, Author, Edition, availability FROM booklist WHERE dept = '$dept' AND ISBN NOT IN 
                        (SELECT isbn FROM borrowers)");

                        if ($data === FALSE) { 
                            throw new Exception(mysqli_error($link));
                        }

                        while ($row = mysqli_fetch_array($data)) {
                            $id = $row['id'];
                            ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['Author']; ?></td> 
                                <td><?php echo $row['Edition']; ?></td>                           
                                <td><?php echo $row['availability']; ?></td>                           
                                <td>
                                    <?php if ($row['availability'] > 0) { ?>
                                        <a href="issuebook.php?id=<?php echo $id; ?>">Issue This Book</a>
                                    <?php } else { ?>
                                        <span style="color: red;">Not Available</span>
                                    <?php } ?>
                                </td>                
                            </tr>
                            <?php 
                        }
                        
                        // Commit transaction if no errors
                        mysqli_commit($link);
                    } catch (Exception $e) {
                        // Rollback transaction if any errors occur
                        mysqli_rollback($link);
                        echo "Failed to load books: " . $e->getMessage();
                    }

                    mysqli_close($link);
                    ?>
                </table>  
            </div>
        </div>       
    </div>
</div>

<?php include('footer.php'); ?>
