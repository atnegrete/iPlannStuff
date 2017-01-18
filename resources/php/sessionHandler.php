<?php

//session_start();

function setLoginSessions($id,$user,$phone_and_carrier){
    $_SESSION["id"] = $id;
    $_SESSION["phone_and_carrier"] = $phone_and_carrier;
    $_SESSION["user"] = $user;
}

function endSession(){
    if(session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
}

?>