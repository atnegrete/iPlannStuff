<?php
if(session_status()!=PHP_SESSION_ACTIVE) { session_start(); }

ini_set('display_errors', 1);


include_once("./statics/connection.php");
include_once("./statics/user.php");

$conn = startConnection();

$user     = $_POST["user"];
$password = $_POST["password"];

$sql    = "SELECT user_name, user_password, user_id
        FROM users
        WHERE user_name = '{$user}'";

$result = $conn->query($sql);

if(is_object($result)){
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $retUserName = $row["user_name"];
            $retPassword = $row["user_password"];
            if ($user === $row["user_name"] && md5($password) === $row["user_password"]) {
                $phone_and_carrier = User::getPhoneNumberWithCarrier($row["user_id"]);
                setLoginSessions($row["user_id"],$row["user_name"], $phone_and_carrier);
                $dt = (new \DateTime())->format('Y-m-d H:i:s') ;
                $retVal["result"] = true;
                $retVal["dt"] = $dt;
                endConnection();
            } else {
                $retVal["result"] = false;
                session_destroy();
                endConnection();
            }
        }
    }else {
        $retVal["result"] = false;
        session_destroy();
        endConnection();
    }
} else {
    $retVal["result"] = false;
    session_destroy();
    endConnection();
}

echo json_encode($retVal);


function setLoginSessions($id,$user,$phone_and_carrier){
    $_SESSION["id"] = $id;
    $_SESSION["phone_and_carrier"] = $phone_and_carrier;
    $_SESSION["user"] = $user;
    $_SESSION["logged_in"] = true;
}
?>