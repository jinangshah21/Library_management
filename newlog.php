<?php
session_start();
require 'connection.php';

if (isset($_POST['login'])) {      
    $username = $_POST['username'];   
    $password = $_POST['password'];
    $user_role = $_POST['userrole'];

    // Define role-based table mapping
    $tableMapping = [
        'student' => 'registration',
        'teacher' => 'te_registration',
        'webadmin' => 'admin_data'
    ];

    // Check if the user role is valid
    if (array_key_exists($user_role, $tableMapping)) {
        // Prepare a parameterized query to prevent SQL injection
        $table = $tableMapping[$user_role];
        $sth = $conn->prepare("SELECT COUNT(id) FROM $table WHERE username = :username AND password = :password");
        $sth->bindParam(':username', $username);
        $sth->bindParam(':password', $password);
        $sth->execute();
        
        // Check if a matching record is found
        $count = $sth->fetchColumn(); 
        if ($count == 1) {
            $_SESSION['username'] = $username;

            // Redirect based on the user role
            if ($user_role == 'student') {
                header('Location: profile.php');
            } elseif ($user_role == 'teacher') {
                header('Location: teprofile.php');
            } elseif ($user_role == 'webadmin') {
                header('Location: adminarea.php');
            }
            exit();
        } else {
            echo "Username and Password could be wrong or you are not a registered " . ucfirst($user_role) . ".";
        }
    } else {
        echo "Invalid user role selected.";
    }
} else {
    header('Location: index.php');
    exit();
}
?>
