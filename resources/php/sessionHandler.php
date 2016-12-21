<?php
session_start();
$retVal = array();


function setLoginSessions($id,$user,$phone_and_carrier){
    $_SESSION["id"] = $id;
    $_SESSION["phone_and_carrier"] = $phone_and_carrier;
    $_SESSION["user"] = $user;
}

function endSession(){
    session_destroy();
}
?>