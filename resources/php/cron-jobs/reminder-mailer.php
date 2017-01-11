<?php
// set_include_path("home/stuffbay/public_html/projects/planner/resources/php/cron-jobs");
// Included files > Go to: parent directory
require_once("/home/stuffbay/public_html/projects/planner/resources/php/statics/connection.php");
require_once("/home/stuffbay/public_html/projects/planner/resources/php/statics/mailer.php");
require_once("/home/stuffbay/public_html/projects/planner/resources/php/statics/reminder.php");
require_once("/home/stuffbay/public_html/projects/planner/resources/php/statics/task.php");

// $base = dirname(dirname(__FILE__));
// echo ($base);

$interval12hr = new DateInterval('PT12H');
$interval1min = new DateInterval('PT45S');

// Get all the reminder dates and the task id's for 24 hr Window (-12 to +12)
$current_datetime = new DateTime("now");

$tz = new DateTimeZone("America/Chicago");
$current_datetime->setTimeZone($tz);

$from_time = $current_datetime->sub($interval12hr);
$mysql_from_datetime = $from_time->format("Y-m-d H:i:s");

// Add interval Twice to account for subtraction.
$to_time = $current_datetime->add($interval12hr);
$to_time = $current_datetime->add($interval12hr);
$mysql_to_datetime = $to_time->format("Y-m-d H:i:s");

$conn = startConnection();

$reminders_to_check = Reminder::getRemindersDATE_TZ_USERID($mysql_from_datetime, $mysql_to_datetime);

$reminders_to_notify = array();
while($reminder = $reminders_to_check->fetch_array(MYSQLI_ASSOC)){
    $cur = (object) $reminder;
    $reminder_datetime = new DateTime($cur->reminder_date, new DateTimeZone($cur->time_zone_value));
    $from_range = new DateTime("now", new DateTimeZone($cur->time_zone_value));
    $from_range->sub($interval1min);
    $to_range = new DateTime("now", new DateTimeZone($cur->time_zone_value));
    $to_range->add($interval1min);
    if($reminder_datetime >= $from_range && $reminder_datetime <= $to_range){
        //var_dump($reminder_datetime->format("Y-m-d H:i:s"));
        array_push($reminders_to_notify, $cur);
        //var_dump($cur);
    }
}

// var_dump($reminders_to_notify);

if(is_array($reminders_to_notify) && sizeof($reminders_to_notify) > 0){
    for($i = 0; $i < sizeof($reminders_to_notify); $i++){
        $cur = $reminders_to_notify[$i];
        $shared = Task::getTaskSharedFriends($cur->task_id, $cur->user_id);
        $cur->shared_msg = "";
        $cur->cc_list = array();
        while($s = $shared->fetch_array(MYSQLI_ASSOC)){
            $s = (object) $s;
            $cur->shared_msg = "* This is a shared task from, ". $cur->user_name . ". *";
            // Mailer::notify($cur);
            array_push($cur->cc_list, $s->user_email);
        }
        Mailer::notify($cur);

    }
}else{
    echo "No reminders to send currently.";
}

endConnection();

?>