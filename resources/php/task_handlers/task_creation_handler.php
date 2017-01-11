
<?php
// Included files > Go to: parent directory
include_once("../statics/connection.php");
include_once("../sessionHandler.php");
include_once("../statics/task.php");

$retVal = array();
if(!isset($_POST["task_name"]) | !isset($_POST["task_created_date"]) | !isset($_SESSION["id"])
    | !isset($_POST["task_due_date"]) | !isset($_POST["task_category_id"])){
    $retVal["error"] = "ERROR: A value not set.";
    echo $retVal;
    exit;
}

$conn = startConnection();

$task_name = $_POST["task_name"];
$created_date = $_POST["task_created_date"];
$due_date = $_POST["task_due_date"];
$category_id = intval($_POST["task_category_id"]);


// Create new task and get the task_id, then check if creation was a success.
$new_task_id = Task::addTask($task_name, $created_date, $_SESSION["id"]);
if($new_task_id === false){
    $retVal["error"] = "ERROR: Failed method -> Task::addTask()";
    endConnection();
    echo $retVal;
    exit;
}

// Add the Category if it has one.
if($category_id > 0){
    Task::modifyTaskCategoryId($new_task_id, $category_id);
}


// Add the due date if it has one.
if($due_date != null && isset($due_date)){
    Task::modifyTaskDueDate($new_task_id, $due_date);
}

// Add the reminders if they are set.
if(isset($_POST["reminder1"]) && $_POST["reminder1"] != null){
    Task::addTaskReminders($_POST["reminder1"], $new_task_id, $_POST["reminder_input1"], $_POST["reminder_select1"]);
}
if(isset($_POST["reminder2"]) && $_POST["reminder2"] != null){
    Task::addTaskReminders($_POST["reminder2"], $new_task_id, $_POST["reminder_input2"], $_POST["reminder_select2"]);
}
if(isset($_POST["reminder3"]) && $_POST["reminder3"] != null){
    Task::addTaskReminders($_POST["reminder3"], $new_task_id, $_POST["reminder_input3"], $_POST["reminder_select3"]);
}
if(isset($_POST["reminder4"]) && $_POST["reminder4"] != null){
    Task::addTaskReminders($_POST["reminder4"], $new_task_id, $_POST["reminder_input4"], $_POST["reminder_select4"]);
}
if(isset($_POST["reminder5"]) && $_POST["reminder5"] != null){
    Task::addTaskReminders($_POST["reminder5"], $new_task_id, $_POST["reminder_input5"], $_POST["reminder_select5"]);
}

$retVal["result"] = "Success creating task.";
echo json_encode($retVal);
endConnection();
?>