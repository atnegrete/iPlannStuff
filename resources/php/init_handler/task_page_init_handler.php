<?php
session_start();
ini_set('display_errors', 1);

// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../statics/category.php");
include_once("../statics/task.php");
include_once("../statics/user.php");

$conn = startConnection();

$retVal = array();
$data = json_decode(file_get_contents("php://input"));

if( !isset($_SESSION["id"]) && (!isset($data->functionname) || !isset($_POST["functionname"]))){
    $retVal["error"] = "ERROR: A value not set.";
}else{
    if(!isset($data->functionname)){
        $functionToUse = $_POST["functionname"];
        //echo $functionToUse;
    }else{
        $functionToUse = $data->functionname;
    }
    if(isset($functionToUse) ){
        switch($functionToUse){
            case "getUserCategories":
                getUserCategories();
                break;
            case "getAllActiveTasks":
                $retVal["all_active_tasks"] = array();
                getAllActiveTasks();
                getTaskReminders($retVal["all_active_tasks"]);
                break;
            case "getRecentlyCompletedTasks":
                $retVal["recent_tasks"] = array();
                getRecentlyCompletedTasks();
                getTaskReminders($retVal["recent_tasks"]);
                break;
            case "getUserFriends":
                $retVal["friends"] = array();
                getUserFriends();
                break;
            default:
                $retVal["error"] = "'functionname' not found.";
                break;
        }
    }
}

// FIXED 
function getUserCategories()
{
    global $retVal;
    $retVal["category_ids"] = array();

    // Get all the category_ids for the user. Then get category_names for each category_id.
    $result = get_result(Category::getUserTaskCategoriesIds($_SESSION["id"]));

    for($i = 0; $i < count($result); $i++){
        $cur = $result[$i];
        //var_dump((object)$cur);
        foreach ($cur as $r)
        {
            //array_push($retVal["category_ids"], $r);
            $temp_name = Category::getCategoryNameById($r);
            $retVal["category_ids"][$r] = array();
            array_push($retVal["category_ids"][$r],$temp_name);
        }
    }
}

// SEMI-FIXED
function getAllActiveTasks(){
    global $retVal;
    // Get all the Tasks from the user_id.
    $tasks = Task::getWeeklyActiveUserTasks($_SESSION["id"]);

    $result = get_result($tasks);

    for($i = 0; $i < count($result); $i++){
        $t = (object) $result[$i];
        $t->shared_friends_id = array();

        $shared_friends = get_result(Task::getTaskSharedFriends($t->task_id, $_SESSION["id"]));

        for($j = 0; $j < count($shared_friends); $j++){
            $friend = (object) $shared_friends[$j];
            array_push($t->shared_friends_id, $friend);
        }
        array_push($retVal["all_active_tasks"], $t);
    }
}

function getRecentlyCompletedTasks(){
    global $retVal, $data;
    // Get all the Tasks from the user_id.
    $tasks = Task::getRecentlyCompletedTasks($_SESSION["id"], $data->day_before);

    $result = get_result($tasks);

    for($i = 0; $i < count($result); $i++){
        $t = (object) $result[$i];
        $t->shared_friends_id = array();

        $shared_friends = get_result(Task::getTaskSharedFriends($t->task_id, $_SESSION["id"]));

        for($j = 0; $j < count($shared_friends); $j++){
            $friend = (object) $shared_friends[$j];
            array_push($t->shared_friends_id, $friend);
        }
        array_push($retVal["recent_tasks"], $t);
    }
}

function getTaskReminders(& $tasks_array){
    if(is_array($tasks_array)){
        for($i = 0; $i < sizeof($tasks_array); $i++){
            $current_task = $tasks_array[$i];
            $tasks_array[$i]->task_reminders = array();

            $result = get_result(Task::getTaskReminders($current_task->task_id));
            for($j = 0; $j < count($result); $j++){
                $cur = (object)$result[$j];
                array_push($tasks_array[$i]->task_reminders, $cur);
            }
        }
    }
}

function getUserFriends(){
    global $retVal;
    
    // Get all the friendships for user_id, then find which is the friend id.
    $friendships = get_result(User::getUserFriends($_SESSION["id"]));
    if(! is_string($friendships)){
        for($i = 0; $i < count($friendships); $i++){
            $f = (object) $friendships[$i];

            // Check if user_one_id is the current user_id
            if($f->user_one_id == $_SESSION["id"]){
                $r = get_result(User::getUserName($f->user_two_id));
                $name = $r[0]['user_name'];
                $friend = new stdClass();
                $friend->name = $name;
                $friend->id = $f->user_two_id;
                $friend->friendship_id = $f->friendship_id;
                array_push($retVal["friends"], $friend);
            }else{
            $r = get_result(User::getUserName($f->user_one_id));
                $name = $r[0]['user_name'];
                $friend = new stdClass();
                $friend->name = $name;
                $friend->id = $f->user_one_id;
                $friend->friendship_id = $f->friendship_id;
                array_push($retVal["friends"], $friend);
            }
        }
    }else{
        $retVal["error"] = "Error loading one or more friends.";
    }
}

endConnection();
echo json_encode($retVal);
?>