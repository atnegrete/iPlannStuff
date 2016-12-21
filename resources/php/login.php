
<?php
include_once("./connection.php");
include_once("./sessionHandler.php");
include_once("./user.php");
include_once("./mailer.php");

$conn = startConnection();

$user     = $_POST["user"];
$password = $_POST["password"];

$sql    = "SELECT user_name, password, id
        FROM users
        WHERE user_name = '{$user}'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $retUserName = $row["user_name"];
        $retPassword = $row["password"];
        if ($user === $row["user_name"] && md5($password) === $row["password"]) {
            $phone_and_carrier = User::getPhoneNumberWithCarrier($row["id"]);
            setLoginSessions($row["id"],$row["user_name"], $phone_and_carrier);
            $retVal["result"] = true;
            endConnection();
        } else {
            $retVal["result"] = false;
            endConnection();
        }
    }
} else {
    $retVal["result"] = false;
    endConnection();
}



echo json_encode($retVal);
?>