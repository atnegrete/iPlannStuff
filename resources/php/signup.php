<?php
include_once("statics/connection.php");
include_once("statics/user.php");

$retVal = array();
$conn = startConnection();

if(!isset($_POST["user"]) || !isset($_POST["password"]) || !isset($_POST["email"]) || !isset($_POST["phone"]) || !isset($_POST["carrier"]) || !isset($_POST["created_date"])){
    $retVal["error"] = "Post failed, a form value not set.";
}

if(User::checkIfUserNameExists($_POST["user"])){
    $retVal["error"] = "Username already exists.";
}

if(User::checkIfEmailExists($_POST["email"])){
    $retVal["error"] = "Email already exists.";
}

$user_id = -1;
if(!isset($retVal["error"])){
    $user_id = User::addUserToDB($_POST["user"], $_POST["password"], $_POST["email"], $_POST["phone"], $_POST["carrier"], $_POST["created_date"], $_POST["time_zone_id"]);
    if($user_id > 0){
        $retVal["result"] = true;
    }else{
        $retVal["error"] = "Failed to add user to DB";
    }
}

// If user account was created successfully, then add the Default categories.
if($user_id > 0){
    User::setDefaultUserCategories($user_id);
}

endConnection();
echo json_encode($retVal);
?>