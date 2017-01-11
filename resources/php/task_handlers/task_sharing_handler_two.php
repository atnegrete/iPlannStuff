<?php
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../sessionHandler.php");
include_once("../statics/user.php");
include_once("../statics/task.php");

$retVal = array();
$conn = startConnection();


if( !isset($_SESSION["id"]) || !isset($_POST["functionname"]) ){
    $retVal["error"] = "ERROR: A value not set.";
}else{
    switch($_POST["functionname"]){
        case "acceptUserRequest":
            acceptUserRequest($_POST["invite_id"]);
            break;
        case "rejectUserRequest":
            rejectUserRequest($_POST["invite_id"]);
            break;
        case "removeFriendShareTask":
            removeFriendShareTask($_POST["task_id"], $_POST["friend_id"]);
            break;
        case "addFriendShareTask":
            addFriendShareTask($_POST["task_id"], $_POST["friend_id"]);
            break;
        default:
            $retVal["error"] = "'functionname' not found.";
            break;
    }
}

function acceptUserRequest($invite_id){
    global $retVal;
    $invite = User::getInviteInfo($invite_id);
    if($invite != null && count($invite) == 1){
        while($i = $invite->fetch_array(MYSQLI_ASSOC)){
            $cur = (object) $i;
            if(User::acceptUserRequest($cur->to_id, $cur->from_id)){
                User::removeInviteRequest($invite_id);
                $retVal["result"] = "Successfully accepted request.";
            }else{
                $retVal["error"] = "Error : Failed to accept request.";
            }
        }
    }else{
        $retVal["error"] = "function : -acceptUserRequest- error finding invite_id.";
    }
}

function rejectUserRequest($invite_id){
    global $retVal;
    if(User::removeInviteRequest($invite_id)){
        $retVal["result"] = "Rejected friendship request.";
    }else{
        $retVal["error"] = "function : -rejectUserRequest- error finding invite_id.";
    }
}

function addFriendShareTask($task_id, $friend_id){
    global $retVal;
    //var_dump($task_id,$friend_id);
    if(Task::addFriendShareTask($task_id, $_SESSION["id"], $friend_id)){
        $retVal["result"] = "Friend has been added to task.";
    }else{
        $retVal["error"] = "There was a problem adding your friend to the task. Please try again later.";
    }
}

function removeFriendShareTask($task_id, $friend_id){
    global $retVal;
    //var_dump($task_id,$friend_id);
    if(Task::removeFriendShareTask($task_id, $_SESSION["id"], $friend_id)){
        $retVal["result"] = "Friend has been removed from task.";
    }else{
        $retVal["error"] = "There was a problem removing your friend from the task. Please try again later.";
    }
}

endConnection();
echo json_encode($retVal);
?>