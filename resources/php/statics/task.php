<?php
class Task {
    // Create given reminder for given task_id
    public static function addTaskReminders($given_reminder_date, $task_id, $reminder_input, $reminder_select){
        global $conn;
        $statement = $conn->stmt_init();
        $statement->prepare("INSERT INTO reminders(task_id, reminder_date, reminder_input, reminder_select) VALUES (?, ?, ?, ?)");
        $statement->bind_param("isis", $task_id, $given_reminder_date, $reminder_input, $reminder_select);
        if(!$statement->execute()){
            echo "Execute failed -addTaskReminders- : (" . $statement->errno.")".$statement->error;
            return false;
        }else{
            $statement->close();
            return true;
        }
    }
    public static function getTaskReminders($task_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("SELECT reminder_id, reminder_date, reminder_input, reminder_select FROM reminders WHERE task_id=?")){
            $statement->bind_param("i", $task_id);
            if(!$statement->execute()){
                echo "Execute failed -getUserTasks-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                //$result = $statement->get_result();
                return $statement;
            }   
        }
    }
    // Add the task, task_id will also act as the id to find the reminders.
    // RETURNS : task_id
    public static function addTask($task_name, $created_date, $user_id){
        global $conn;
        $statement = $conn->stmt_init();
        $active = 1;
        if($statement->prepare("INSERT INTO tasks(task_name, created_date, user_id, task_active) VALUES (?, ?, ?, ?)")){
            $statement->bind_param("sssi", $task_name, $created_date, $user_id, $active);
            if(!$statement->execute()){
                echo "Execute failed -addTask-: (" . $statement->errno.")".$statement->error;
                return false;
            }
            $statement->close();
            return $conn->insert_id;
        }
    }
    public static function modifyTaskDueDate($task_id, $due_date){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("UPDATE tasks SET due_date=? WHERE task_id=?")){
            $statement->bind_param("si", $due_date, $task_id);
            if(!$statement->execute()){
                echo "Execute failed -modifyTaskDueDate-: (" . $statement->errno.")".$statement->error;
                return false;
            }else{
                return true;
            }
        }
    }
    public static function modifyTaskCompletedDate($task_id, $completed_date){
        global $conn;
        $statement = $conn->stmt_init();
        $active = 0;
        if($statement->prepare("UPDATE tasks SET completed_date=?, task_active=? where task_id=?")){
            $statement->bind_param("sii", $completed_date, $active, $task_id);
            if(!$statement->execute()){
                $error = "Execute failed -modifyTaskCompletedDate-: (" . $statement->errno.")".$statement->error;
                echo $error;
                $statement->close();
                return $error;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function modifyTaskUncompleted($task_id){
        global $conn;
        $statement = $conn->stmt_init();
        $active = 1;
        $completed_date = null;
        if($statement->prepare("UPDATE tasks SET completed_date=?, task_active=? where task_id=?")){
            $statement->bind_param("sii", $completed_date, $active, $task_id);
            if(!$statement->execute()){
                $error = "Execute failed -modifyTaskUncompleted-: (" . $statement->errno.")".$statement->error;
                echo $error;
                $statement->close();
                return $error;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function modifyTaskCategoryId($task_id, $category_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("UPDATE tasks SET category_id=? WHERE task_id=?")){
            $statement->bind_param("ii", $category_id, $task_id);
            if(!$statement->execute()){
                echo "Execute failed -modifyTaskCategoryId-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function getWeeklyActiveUserTasks($user_id){
        global $conn;
        $statement = $conn->stmt_init();
        $active = 1;
        if($statement->prepare("SELECT task_id, task_name, created_date, due_date, category_id FROM tasks WHERE user_id=? AND task_active=?")){
            $statement->bind_param("ii", $user_id, $active);
            if(!$statement->execute()){
                echo "Execute failed -getUserTasks-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                return $statement;
            }   
        }
    }
    // public static function getUserOverdueTasks($user_id, $current_datetime){
    //     global $conn;
    //     $statement = $conn->stmt_init();
    //     $active = 1;
    //     if($statement->prepare("SELECT task_id, task_name, created_date, due_date, category_id  FROM tasks WHERE user_id=? AND task_active=? AND due_date <=?")){
    //         $statement->bind_param("iis", $user_id, $active, $current_datetime);
    //         if(!$statement->execute()){
    //             echo "Execute failed -getUserTasks-: (" . $statement->errno.")".$statement->error;
    //             $statement->close();
    //             return false;
    //         }else{
    //             $result = $statement->get_result();
    //             return $result;
    //         }   
    //     }
    // }

    public static function getRecentlyCompletedTasks($user_id, $day_before){
        global $conn;
        $statement = $conn->stmt_init();
        $active = 0;
        if($statement->prepare("SELECT task_id, task_name, created_date, due_date, category_id  FROM tasks WHERE user_id=? AND task_active=? AND completed_date >=?")){
            $statement->bind_param("iis", $user_id, $active, $day_before);
            if(!$statement->execute()){
                echo "Execute failed -getUserTasks-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                //$result = $statement->get_result();
                return $statement;
            }   
        }
    }

    public static function deleteTaskReminders($task_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("DELETE FROM reminders WHERE task_id=?")){
            $statement->bind_param("i", $task_id);
            if(!$statement->execute()){
                $error = "Execute failed -deleteTaskReminders-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return $error;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function deleteTask($task_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("DELETE FROM tasks WHERE task_id=?")){
            $statement->bind_param("i", $task_id);
            if(!$statement->execute()){
                $error = "Execute failed -deleteTask-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return $error;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function getTaskSharedFriends($task_id, $user_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("SELECT shared_task.from_id, shared_task.to_id, users.user_name, users.user_email
                                FROM shared_task, tasks, users
                                WHERE tasks.user_id=? AND tasks.task_id=? AND shared_task.task_id=? AND shared_task.to_id=users.user_id")){
            $statement->bind_param("iii", $user_id, $task_id, $task_id);
            if(!$statement->execute()){
                echo "Execute failed -getTaskSharedFriends-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                ///$result = $statement->get_result();
                return $statement;
            }   
        }
    }
    
    public static function addFriendShareTask($task_id, $user_id, $friend_id) {
        global $conn;
        $statement = $conn->stmt_init();
        $active = 1;
        if($statement->prepare("INSERT INTO shared_task(task_id, from_id, to_id) Values(?, ?, ?)")){
            $statement->bind_param("iii", $task_id, $user_id, $friend_id);
            if(!$statement->execute()){
                echo "Execute failed -addFriendShareTask-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                $statement->close();
                return true;
            }
        }
    }
    public static function removeFriendShareTask($task_id, $user_id, $friend_id) {
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("DELETE FROM shared_task WHERE task_id = ? AND from_id = ? AND to_id = ?")){
            $statement->bind_param("iii", $task_id, $user_id, $friend_id);
            if(!$statement->execute()){
                $error = "Execute failed -removeFriendShareTask-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return $error;
            }else{
                $statement->close();
                return true;
            }
        }
    }
}
?>