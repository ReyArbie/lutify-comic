<?php

    $hostname = "localhost";

    $user_db = "root";
    $pass_db = "";
    $db_name = "lutify";

    $conn = new mysqli($hostname, $user_db, $pass_db);

    if ($conn->connect_error){
        die("Connection Failed : " . $conn->connect_error);
    }
    else {
        mysqli_select_db($conn, $db_name);
    }

?>