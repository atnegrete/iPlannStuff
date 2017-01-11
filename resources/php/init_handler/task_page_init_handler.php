
<?php
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../sessionHandler.php");
include_once("../statics/category.php");
include_once("../statics/task.php");
include_once("../statics/user.php");

$retVal = array();
$data = json_decode(file_get_contents("php://input"));

if( !isset($_SESSION["id"]) || !isset($data->functionname) ){
    $retVal["error"] = "ERROR: A value not set.";

}else{
    switch($data->functionname){
        case "getUserCategories":
            $conn = startConnection();
            getUserCategories();
            break;
        case "getAllActiveTasks":
            $conn = startConnection();
            $retVal["all_active_tasks"] = array();
            getAllActiveTasks();
            getTaskReminders($retVal["all_active_tasks"]);
            break;
        case "getRecentlyCompletedTasks":
            $conn = startConnection();
            $retVal["recent_tasks"] = array();
            getRecentlyCompletedTasks();
            getTaskReminders($retVal["recent_tasks"]);
            break;
        case "getUserFriends":
            $conn = startConnection();
            $retVal["friends"] = array();
            getUserFriends();
            break;
        default:
            $retVal["error"] = "'functionname' not found.";
            break;
    }
}


function getUserCategories()
{
    global $retVal;
    $retVal["category_ids"] = array();

    // Get all the category_ids for the user. Then get category_names for each category_id.
    $category_ids = Category::getUserTaskCategoriesIds($_SESSION["id"]);

    while ($row = $category_ids->fetch_array(MYSQLI_ASSOC))
    {
        foreach ($row as $r)
        {
            //array_push($retVal["category_ids"], $r);
            $temp_name = Category::getCategoryNameById($r);
            $retVal["category_ids"][$r] = array();
            array_push($retVal["category_ids"][$r],$temp_name);
        }
    }
}

function getAllActiveTasks(){
    global $retVal;
    // Get all the Tasks from the user_id.
    $tasks = Task::getWeeklyActiveUserTasks($_SESSION["id"]);

    while($task = $tasks->fetch_array(MYSQLI_ASSOC)){
        $t = (object) $task;
        $t->shared_friends_id = array();
        $shared_friends = Task::getTaskSharedFriends($t->task_id, $_SESSION["id"]);
        if($shared_friends != null && !(is_string($shared_friends))){
            while($f = $shared_friends->fetch_array(MYSQLI_ASSOC)){
                $friend = (object)$f;
                array_push($t->shared_friends_id,$friend);
            }
            array_push($retVal["all_active_tasks"], $t);
        }
    }
}

function getRecentlyCompletedTasks(){
    global $retVal, $data;
    // Get all the Tasks from the user_id.
    $tasks = Task::getRecentlyCompletedTasks($_SESSION["id"], $data->day_before);

    while($task = $tasks->fetch_array(MYSQLI_ASSOC)){
        $t = (object) $task;
        $t->shared_friends_id = array();
        $shared_friends = Task::getTaskSharedFriends($t->task_id, $_SESSION["id"]);
        if($shared_friends != null && !(is_string($shared_friends))){
            while($f = $shared_friends->fetch_array(MYSQLI_ASSOC)){
                $friend = (object)$f;
                array_push($t->shared_friends_id,$friend);
            }
            array_push($retVal["recent_tasks"], $t);
        }
    }
}

function getTaskReminders(& $tasks_array){
    if(is_array($tasks_array)){
        for($i = 0; $i < sizeof($tasks_array); $i++){
            $current_task = $tasks_array[$i];
            $tasks_array[$i]->task_reminders = array();
            $result = Task::getTaskReminders($current_task->task_id);
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                array_push($tasks_array[$i]->task_reminders, (object) $row);
            }
        }
    }
}

function getUserFriends(){
    global $retVal;
    
    // Get all the friendships for user_id, then find which is the friend id.
    $friendships = User::getUserFriends($_SESSION["id"]);
    if(! is_string($friendships)){
        while($friendship = $friendships->fetch_array(MYSQLI_ASSOC)){
            $f = (object) $friendship;
            // Check if user_one_id is the current user_id
            if($f->user_one_id == $_SESSION["id"]){
                if($friend_name = User::getUserName($f->user_two_id)){
                    $name = (object) $friend_name->fetch_array(MYSQLI_ASSOC);
                    $friend = new stdClass();
                    $friend->name = $name->user_name;
                    $friend->id = $f->user_two_id;
                    array_push($retVal["friends"], $friend);
                }else{
                    $retVal["error"] = "Error - Could not find friend.";
                }
            }else{
                if($friend_name = User::getUserName($f->user_one_id)){
                    $name = (object) $friend_name->fetch_array(MYSQLI_ASSOC);
                    $friend = new stdClass();
                    $friend->name = $name->user_name;
                    $friend->id = $f->user_one_id;
                    array_push($retVal["friends"], $friend);
                }else{
                    $retVal["error"] = "Error - Could not find friend.";
                }
            }
        }
    }else{
        $retVal["error"] = "Error loading one or more friends.";
    }
}

endConnection();
echo json_encode($retVal);
?>