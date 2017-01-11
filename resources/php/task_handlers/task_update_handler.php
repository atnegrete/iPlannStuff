
<?php
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../sessionHandler.php");
include_once("../statics/task.php");

$retVal = array();
$data = json_decode(file_get_contents("php://input"));

if( !isset($_SESSION["id"]) || !isset($_POST["functionname"]) ){
    $retVal["error"] = "ERROR: A value not set.";

}else{
    switch($_POST["functionname"]){
        case "updateTaskToCompleted":
            $conn = startConnection();
            updateTaskToCompleted($_POST["task_id"], $_POST["task_completed_date"]);
            break;
        case "updateTaskToUncompleted":
            $conn = startConnection();
            updateTaskToUncompleted($_POST["task_id"]);
            break;
        case "deleteTask":
            $conn = startConnection();
            deleteTask($_POST["task_id"]);
            break;
        default:
            $retVal["error"] = "'functionname' not found.";
            break;
    }
}

function updateTaskToCompleted($task_id, $task_completed_date){
    global $retVal;
    if($val = Task::modifyTaskCompletedDate($task_id, $task_completed_date)){
        $retVal["result"] = "Task Completed!";
    }else{
        $retVal["error"] = $val;
    }
}

function updateTaskToUncompleted($task_id){
    global $retVal;
    if($val = Task::modifyTaskUncompleted($task_id)){
        $retVal["result"] = "Task marked as not Complete.";
    }else{
        $retVal["error"] = $val;
    }
}

function deleteTask($task_id){
    global $retVal;
    if($val = Task::deleteTaskReminders($task_id)){
        if($val2 = Task::deleteTask($task_id)){
            $retVal["result"] = "Task has been removed.";
        }else{
            $retVal["error"] = $val2;
        }
    }else{
        $retVal["error"] = $val;
    }
}

endConnection();
echo json_encode($retVal);
?>