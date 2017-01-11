<?php
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../sessionHandler.php");
include_once("../statics/user.php");

$retVal = array();
$data = json_decode(file_get_contents("php://input"));
$conn = startConnection();

if( !isset($_SESSION["id"]) || !isset($data->functionname) ){
    $retVal["error"] = "ERROR: A value not set.";
}else{
    switch($data->functionname){
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
    // TODO: Check if user is inviting himself.
    // TODO: Check if user is inviting someone already a friend.
    
    global $retVal;
    if(User::checkIfEmailExists($invite_email)){
        $result = User::getUserIdFromEmail($invite_email);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $cur = (object) $row;
            $to_id = $cur->user_id;
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
    }else{
        $retVal["error"] = "User for given email not found.";
    }
}

function getFriendRequests(){
    global $retVal;
    if(($requests = User::getFriendRequests($_SESSION["id"])) != false){
        $retVal["result"] = array();
        //var_dump($requests);
        if(count($requests)){
            while($request = $requests->fetch_array(MYSQLI_ASSOC)){
                $cur = (object) $request;
                array_push($retVal["result"], $cur);
            }
        }
    }else {
        $retVal["error"] = "Failed to load friend requests.";
    }
}

endConnection();
echo json_encode($retVal);
?>