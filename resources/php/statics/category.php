<?php
class Category {

    // RETURNS: all category_id's' for given user_id
    public static function getUserTaskCategoriesIds($user_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("SELECT category_id FROM user_categories WHERE user_id=?")){
            $statement->bind_param("i", $user_id);
            if(!$statement->execute()){
                echo "Execute failed -getUserTaskCategoriesIds-: (" . $statement->errno.")".$statement->error;
                $statement->close();
                return false;
            }else{
                return $statement;
            }
        }
    }
    // Create given category and return it's Id.'
    public static function addCategory($category_name){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("INSERT INTO categories(category_name) VALUES (?)")){
            $statement->bind_param("s", $category_name);
            if(!$statement->execute()){
                echo "Execute failed -addCategory-: (" . $statement->errno.")".$statement->error;
                return false;
            }else{
                return true;
            }
        }
    }
    // // RETURNS : category_id if it exists, -1 if it doesn't.
    // public static function getCategoryIdByName($category_name){
    //     global $conn;
    //     $statement = $conn->stmt_init();
    //     if($statement->prepare("SELECT category_id FROM categories WHERE category_name= ?")){
    //         $statement->bind_param("s", $category_name);
    //         if(!$statement->execute()){
    //             echo "Execute failed -getCategoryId- : (" . $statement->errno.")".$statement->error;
    //         }else{
    //             $id = -1;
    //             $statement->bind_result($id);
    //             $statement->fetch();
    //             $statement->close();
    //             if($id > 0){
    //                 return $id;
    //             }else{
    //                 return -1;
    //             }
    //         }
    //     }
    // }
    // RETURNS : category_name if it exists, -1 if it doesn't.
    public static function getCategoryNameById($category_id){
        global $conn;
        $statement = $conn->stmt_init();
        if($statement->prepare("SELECT category_name FROM categories WHERE category_id= ?")){
            $statement->bind_param("i", $category_id);
            if(!$statement->execute()){
                echo "Execute failed -getCategoryId- : (" . $statement->errno.")".$statement->error;
            }else{
                $name = -1;
                $statement->bind_result($name);
                $statement->fetch();
                $statement->close();
                return $name;
            }
        }
    }
}

?>