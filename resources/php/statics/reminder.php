<?php

class Reminder {

    public static function getRemindersDATE_TZ_USERID($from_time, $to_time){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("SELECT DISTINCT reminders.reminder_date, time_zones.time_zone_value, users.user_id, users.user_email, users.user_phone, users.user_carrier, users.user_name, tasks.task_id, tasks.due_date, tasks.task_name
                                FROM reminders, users, time_zones, tasks
                                WHERE reminders.reminder_date>=? AND reminders.reminder_date<=?
                                AND reminders.task_id = tasks.task_id AND tasks.user_id = users.user_id AND users.user_time_zone_id = time_zones.time_zone_id")){
            $statement->bind_param("ss", $from_time, $to_time);
            if(!$statement->execute()){
                echo "Execute failed -getRemindersDATE_TZ_USERID-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                //$result = get_result($statement);
                return $statement;
            }   
        }
    }
}
?>