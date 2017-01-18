<?php
$username = "anegrete_admin";
$password = "ALan@12@123";
$dbServer = "50.87.151.230"; 
$dbName   = "anegrete_iplanstuff_tasks_db";
// $username = "root";
// $password = "";
// $dbServer = "localhost";
// $dbName = "iplanstuff_tasks_db";

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

function get_result( $Statement ) {
    $RESULT = array();
    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}
?>