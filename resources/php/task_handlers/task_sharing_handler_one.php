<?php
session_start();

ini_set('display_errors', 1);
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../statics/user.php");

$retVal = array();
$data = json_decode(file_get_contents("php://input"));
$conn = startConnection();

if( !isset($_SESSION["id"]) && (!isset($data->functionname) || !isset($_POST["functionname"]))){
    $retVal["error"] = "ERROR: A value not set.";
}else{
    if(!isset($data->functionname)){
        $functionToUse = $_POST["functionname"];
        //echo $functionToUse;
    }else{
        $functionToUse = $data->functionname;
    }
    switch($functionToUse){
        case "sendInvite":
            sendInvite($_SESSION["id"], $data->invite_email);
            break;
        case "getFriendRequests":
            getFriendRequests();
            break;
        default:
            $retVal["error"] = "'functionname' not found.";
            break;
    }
}

function sendInvite($user_id, $invite_email){
    // TODO: Check if user is inviting someone already a friend.
    
    global $retVal;
    if(User::checkIfEmailExists($invite_email)){
        $result = get_result(User::getUserIdFromEmail($invite_email));

        for($i = 0; $i < count($result); $i++){
            $cur = (object) $result[$i];
            $to_id = $cur->user_id;

            // Check if invite is to himself.
            if($to_id == $_SESSION["id"]){
                $retVal["error"] = "Can't invite yourself.";
            }else{
                // Check that there isn't already an invite
                if(!User::checkIfInviteExists($user_id, $to_id)){
                    if($val = User::sendInvite($user_id, $to_id)){
                        $retVal["result"] = "Invite sent. Your friend will be notified.";
                    }else{
                        $retVal["error"] = $val;
                    }
                }else{
                    $retVal["error"] = "There is already an invite for pending between you and your friend.";
                }
            }
        }
    }else{
        $retVal["error"] = "User for given email not found.";
    }
}
function getFriendRequests(){
    global $retVal;
    $retVal["result"]  = array();

    $requests = get_result(User::getFriendRequests($_SESSION["id"]));

    for($i = 0; $i < count($requests); $i++){
        $request = (object) $requests[$i];
        array_push($retVal["result"], $request);
    }
}

endConnection();
echo json_encode($retVal);
?>