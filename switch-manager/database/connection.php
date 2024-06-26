<?php
    $servername = "localhost";
    $username = "switchmanager";
    $password = "SwitchmanagerUdesc123!";
    $dbname = "switchmanagerdb";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>