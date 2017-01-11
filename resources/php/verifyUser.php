<?php
include_once("./sessionHandler.php");


if(isset($_POST["functionname"])){
    if($_POST["functionname"] == "verifyUser"){
        if(isset($_SESSION["user"])){
            echo json_encode(true);
        }else{
            endSession();
            echo json_encode(false);
        }
    }
}

if(isset($_POST["functionname"])){
    if($_POST["functionname"] == "logout"){
        endSession();
        echo json_encode(true);
    }
}

if(isset($_POST["functionname"])){
    if($_POST["functionname"] == "getUserName"){
        include_once("./user.php");
        $retVal = array();
        if($val = User::getUserName($_SESSION["id"])){
            $retVal["user_name"] = $val;
        }else{
            $retVal["error"] = $val;
        }
        echo json_decode($retVal);
    }
}

?>