<?php

class Mailer {

    public static function sendEmail($to, $subject, $message, $headers){
        //$message = wordwrap($message,100);
        //var_dump($message);
        echo("Email: " . mail($to,$subject,$message,$headers) . "\r\n");
    }

    public static function sendText($reminder_object){
        $to = ", ".$reminder_object->user_phone . $reminder_object->user_carrier;
        $subject = "iPlanStuff - Reminder";
        
        $time = strtotime($reminder_object->due_date);
        $date = date("m/d/y g:i A", $time);

        $message = "This is an automic reminder for Task: ".$reminder_object->task_name.". \r\n".
                    "It's due on ".$date.". \r\n".
                    "Enjoy your day! No need to stress about forgetting your tasks now. :)";
        $message = wordwrap($message,70);
        $headers = 'From: stuffbay@iplanstuff.com'. "\r\n";
        $headers .= 'Reply-To: do-not-reply@iplanstuff.com'. "\r\n";
        echo("Text: " . mail($to, $subject, $message, $headers) . "\r\n");
    }

    public static function notify($reminder_object){
        $to = $reminder_object->user_email;
        Mailer::sendText($reminder_object);

        $time = strtotime($reminder_object->due_date);
        $date = date("m/d/y g:i A", $time);
        
        $subject = "iPlanStuff - Reminder";
        $message = '
        <html>
        <head>
        <title>iPlanStuff Reminders</title>
        </head>
        <body>
        <h3>This is an automic reminder for Task: '.$reminder_object->task_name.'</h3>
        <h4> '.$reminder_object->shared_msg.'</h4>
        <p>Don\'t forget about your tasks, we\'ve got you covered! Live stress free, and enjoy your day!</p>
        <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        </style>
        <table>
            <tr>
            <th>Task</th> <th></th>     <th>Due On</th>
            </tr>
            <tr>
            <td>'.$reminder_object->task_name.'    </td><td>    </td><td>'.$date.'</td>
            </tr>
        </table>
        </body>
        </html>
        ';
        //var_dump($message);

        $headers = 'MIME-Version: 1.0'. "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        //$headers .= 'To: '.$reminder_object->user_email;
        $headers .= 'From: stuffbay@iplanstuff.com'. "\r\n";
        $headers .= 'Reply-To: do-not-reply@iplanstuff.com'. "\r\n";

        if(sizeof($reminder_object->cc_list > 0)){
            $headers .= "CC: ";
            for($i = 0; $i < sizeof($reminder_object->cc_list); $i++){
                $headers .= $reminder_object->cc_list[$i]. ", ";
            }
        }


        Mailer::sendEmail($to, $subject, $message, $headers);
    }
}

?>