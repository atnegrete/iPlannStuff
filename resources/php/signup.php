<?php
include_once("connection.php");
include_once("user.php");

$retVal = array();
$conn = startConnection();

if(!isset($_POST["user"]) || !isset($_POST["password"]) || !isset($_POST["email"]) || !isset($_POST["phone"]) || !isset($_POST["carrier"])){
    $retVal["error"] = "Post failed, a form value not set.";
}

if(User::checkIfUserNameExists($_POST["user"])){
    $retVal["error"] = "Username already exists.";
}

if(User::checkIfEmailExists($_POST["email"])){
    $retVal["error"] = "Email already exists.";
}

if(!isset($retVal["error"])){
    $val;
    if($val = User::addUserToDB($_POST["user"], $_POST["password"], $_POST["email"], $_POST["phone"], $_POST["carrier"])){
        $retVal["result"] = $val;
    }else{
        $retVal["error"] = $val;
    }
}

endConnection();
echo json_encode($retVal);
?>