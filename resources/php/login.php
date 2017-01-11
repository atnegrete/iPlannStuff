
<?php
include_once("./statics/connection.php");
include_once("./sessionHandler.php");
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
                $retVal["result"] = true;
                endConnection();
            } else {
                $retVal["result"] = false;
                endConnection();
            }
        }
    }else {
        $retVal["result"] = false;
        endConnection();
    }
} else {
    $retVal["result"] = false;
    endConnection();
}



echo json_encode($retVal);
?>