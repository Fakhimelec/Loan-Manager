<?php
    # MySQL Database Connection
    # MySQLi Object Oriented Way
    $servername = "localhost";
    $username = "pcbexper_mainuser";
    $password = "&D@ofw(k1[{E";
    $dbname = "pcbexper_db";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        echo "Connection Error";
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->query("SET character_set_results=utf8;");
    $conn->query("SET character_set_client=utf8;");
    $conn->query("SET character_set_connection=utf8;");
    $conn->query("SET character_set_database=utf8;");
    $conn->query("SET character_set_server=utf8;");
?>