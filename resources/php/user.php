<?php

    class User {

        public static function addUserToDB($user_name, $password, $email, $phone, $carrier){
            global $conn;
            $password = md5($password);
            $sql = "INSERT INTO users (user_name, password, email, phone, carrier)
                    VALUES ('{$user_name}', '{$password}', '{$email}', '{$phone}', '{$carrier}')";
            return checkSQLCommand($conn,$sql);
        }

        public static function checkIfUserNameExists($user_name){
            global $conn;
            $sql = "SELECT *
                    FROM users
                    WHERE user_name = '{$user_name}'";
            $result = $conn->query($sql);
            return $result->num_rows != 0;
        }

        public static function checkIfEmailExists($email){
            global $conn;
            $sql = "SELECT * 
                    FROM users
                    WHERE email = '{$email}'";
            $result = $conn->query($sql);
            return $result->num_rows != 0;
        }

        public static function getPhoneNumberWithCarrier($id){
            global $conn;
            $sql = "SELECT phone, carrier
                    FROM users
                    WHERE id = '{$id}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            return $row["phone"].$row["carrier"];
        }
    }
?>