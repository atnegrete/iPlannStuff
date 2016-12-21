<?php
$username = "chozenalan";
$password = "#Tl,,.Q6srcZ";
$dbServer = "107.180.47.59"; 
$dbName   = "iplanstuff_tasks_db";


function startConnection(){
    global $dbServer,$username,$password,$dbName, $conn;
    $conn = new mysqli($dbServer, $username, $password, $dbName);
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        return $conn;
    }
}

function endConnection(){
    global $conn;
    $conn->close();
}

function checkSQLCommand($connection, $sqlcommand){
    if ($connection->query($sqlcommand)) {
        return true;
    } else {
        echo "Error: " . $sqlcommand . "<br>" . $connection->error;
        return "Error: " . $sqlcommand . "<br>" . $connection->error;
    }
}
?>