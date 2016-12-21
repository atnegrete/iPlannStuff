<?php

// $msg = "Test - This is the actual email msg.";
// $to = "alan.t.negrete@gmail.com";
// $subject = "Test : 1";
// $headers = 'From: stuffbay@iplanstuff.com' . "\r\n" .
//     'Reply-To: do-not-reply@iplanstuff.com' . "\r\n" .
//     'X-Mailer: PHP/' . phpversion();

// // use wordwrap() if lines are longer than 70 characters
// $msg = wordwrap($msg,70);

// // send email
// echo(mail($to,$subject,$msg,$headers));

class Mailer {

    public static function sendText($to, $subject, $message, $headers){
        $message = wordwrap($message,70);
        mail($to,$subject,$msg,$headers);
    }
}

?>