<?php

    class User {
        
        public static function addUserToDB($user_name, $password, $email, $phone, $carrier, $created_date, $user_time_zone_id){
            global $conn;
            $password = md5($password);
            $active = 1;
            $statement = $conn->stmt_init();
            if($statement->prepare("INSERT INTO users(user_name, user_password, user_email, user_phone, user_carrier, user_account_active, user_account_created_date, user_time_zone_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")){
                $statement->bind_param("sssssisi",$user_name, $password, $email, $phone, $carrier, $active, $created_date, $user_time_zone_id);
                if(!$statement->execute()){
                    echo "Execute failed -addUserToDB-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return -1;
                }else{
                    $user_id = $conn->insert_id;
                    $statement->close();
                    return $user_id;
                }
            }
        }

        public static function getUserTimeZone($user_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT time_zone_value FROM time_zones,users WHERE user_id=? AND user_time_zone_id=time_zone_id")){
                $statement->bind_param("i", $user_id);
                if(!$statement->execute()){
                    echo "Execute failed -getUserTimeZone-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return false;
                }else{
                    $result = $statement->get_result();
                    return $result;
                }   
            }
        }

        public static function getUserName($user_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT user_name FROM users WHERE user_id=?")){
                $statement->bind_param("i", $user_id);
                if(!$statement->execute()){
                    echo "Execute failed -checkIfUserNameExists-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return false;
                }else{
                    $result = $statement->get_result();
                    return $result;
                }   
            }
        }

        public static function checkIfUserNameExists($user_name){
            global $conn;
            $sql = "SELECT *
                    FROM users
                    WHERE user_name = '{$user_name}'";
            $result = mysqli_query($conn, $sql);
            return mysqli_num_rows($result) > 0;
        }

        public static function checkIfEmailExists($email){
            global $conn;
            $sql = "SELECT * 
                    FROM users
                    WHERE user_email = '{$email}'";
            $result = mysqli_query($conn, $sql);
            return mysqli_num_rows($result) > 0;
        }

        public static function getPhoneNumberWithCarrier($id){
            global $conn;
            $sql = "SELECT user_phone, user_carrier
                    FROM users
                    WHERE user_id = '{$id}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            return $row["user_phone"].$row["user_carrier"];
        }

        public static function getUserIdFromEmail($email){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT user_id FROM users WHERE user_email=?")){
                $statement->bind_param("s", $email);
                if(!$statement->execute()){
                    echo "Execute failed -getUserIdFromEmail-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return false;
                }else{
                    $result = $statement->get_result();
                    return $result;
                }   
            }
        }

        public static function setDefaultUserCategories($user_id){
            global $conn;
            $statement = $conn->stmt_init();
            $statement->prepare("INSERT INTO user_categories(user_id, category_id) VALUES (?, ?)");
            for($i = 1; $i < 6; $i++){
                $statement->bind_param("ii",$user_id, $i);
                $statement->execute();
            }
            $statement->close();
        }

        public static function sendInvite($user_id, $to_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("INSERT INTO Invite(from_id, to_id) VALUES (?, ?)")){
                $statement->bind_param("ii", $user_id, $to_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -sendInvite-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return $error;
                }else{
                    return true;
                }  
            }else{
                $error =  "Prepare failed -sendInvite-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return $error;
            }
        }

        public static function checkIfInviteExists($user_id, $to_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT invite_id FROM Invite WHERE (from_id=? AND to_id=?) OR (to_id=? AND from_id=?)")){
                $statement->bind_param("iiii", $user_id, $to_id, $user_id, $to_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -checkIfInviteExists-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return $error;
                }else{
                    $count = count($statement->fetch());
                    $statement->close();
                    return $count > 0;
                }  
            }else{
                $error =  "Prepare failed -checkIfInviteExists-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return $error;
            }
        }

        public static function getFriendRequests($user_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT invite.invite_id, users.user_name FROM invite, users WHERE invite.to_id=? AND invite.from_id=users.user_id")){
                $statement->bind_param("i", $user_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -getFriendRequests-: (" . $statement->errno.")".$statement->error;
                    $statement->close();
                    return false;
                }else{
                    return $statement->get_result();
                }  
            }else{
                $error =  "Prepare failed -getFriendRequests-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }
        }

        public static function acceptUserRequest($user_one_id, $user_two_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("INSERT INTO friend(user_one_id, user_two_id) VALUES (?, ?)")){
                $statement->bind_param("ii", $user_one_id, $user_two_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -acceptUserRequest-: (" . $statement->errno.")".$statement->error;
                    echo $error;
                    return false;
                }else{
                    return true;
                }  
            }else{
                $error =  "Prepare failed -acceptUserRequest-: (" . $statement->errno.")".$statement->error;
                echo $error;
                return false;
            }
        }

        public static function removeInviteRequest($invite_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("DELETE FROM invite WHERE invite_id=?")){
                $statement->bind_param("i", $invite_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -removeInviteRequest-: (" . $statement->errno.")".$statement->error;
                    echo $error;
                    return false;
                }else{
                    return true;
                }  
            }else{
                $error =  "Prepare failed -removeInviteRequest-: (" . $statement->errno.")".$statement->error;
                echo $error;
                return false;
            }
        }

        public static function getInviteInfo($invite_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT from_id, to_id FROM invite WHERE invite_id=?")){
                $statement->bind_param("i", $invite_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -getInviteInfo-: (" . $statement->errno.")".$statement->error;
                    echo $error;
                    return $error;
                }else{
                    return $statement->get_result();
                }  
            }else{
                $error =  "Prepare failed -getInviteInfo-: (" . $statement->errno.")".$statement->error;
                echo $error;
                return $error;
            }
        }

        public static function getUserFriends($user_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("SELECT * FROM friend WHERE user_one_id=? OR user_two_id=?")){
                $statement->bind_param("ii", $user_id, $user_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -getUserFriends-: (" . $statement->errno.")".$statement->error;
                    echo $error;
                    return $error;
                }else{
                    return $statement->get_result();
                }  
            }else{
                $error =  "Prepare failed -getUserFriends-: (" . $statement->errno.")".$statement->error;
                echo $error;
                return $error;
            }
        }

        public static function removeFriendship($friendship_id){
            global $conn;
            $statement = $conn->stmt_init();
            if($statement->prepare("DELETE FROM friend WHERE friendship_id = ?")){
                $statement->bind_param("i", $friendship_id);
                if(!$statement->execute()){
                    $error =  "Execute failed -removeFriendship-: (" . $statement->errno.")".$statement->error;
                    echo $error;
                    return false;
                }else{
                    return true;
                }  
            }else{
                $error =  "Prepare failed -removeFriendship-: (" . $statement->errno.")".$statement->error;
                echo $error;
                return false;
            }
        }
        
    }
?>