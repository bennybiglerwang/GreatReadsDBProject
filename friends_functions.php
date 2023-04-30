
<?php
function checkFriendshipStatus($username1, $username2){
    // echo $username1;
    // echo $username2;
    if($username1 == $username2){
        $status = "self";
    } elseif(checkFriendSent($username1, $username2)) { 
        if(checkFriendReceived($username1, $username2)) {
            $status = "friends";
        } else {
            $status = "request sent";
        }
    } elseif(checkFriendReceived($username1, $username2)) {
        $status = "request received";
    }  else {
        $status = "not friends";
    }

    return $status;
    
}

function checkFriendSent($username1, $username2){
    global $db;
    
    $query= "select * from  friends where username1 = :username1 and username2 = :username2";
    $statement = $db->prepare($query);
    $statement->bindValue(':username1', $username1);
    $statement->bindValue(':username2', $username2);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
    // if($result > 0){
    //     return True;
    // }
    // return False;
}

function checkFriendReceived($username1, $username2){
    global $db;
    
    $query= "select * from friends where username1 = :username2 and username2 = :username1";
    $statement = $db->prepare($query);
    $statement->bindValue(':username1', $username1);
    $statement->bindValue(':username2', $username2);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

function addFriend($id, $username1, $username2){

    global $db;
    
    $query= "insert into friends values (NULL,  :username1, :username2)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username1', $username1);
    $statement->bindValue(':username2', $username2);
    $statement->execute();
    $statement->closeCursor();
}

function deleteFriend($username1, $username2){

    global $db;
    
    $query= "delete from friends where username1 = :username1 and username2 = :username2 or username1 = :username2 and username2 = :username1";
    $statement = $db->prepare($query);
    $statement->bindValue(':username1', $username1);
    $statement->bindValue(':username2', $username2);
    $statement->execute();
    $statement->closeCursor();
}

function check_user_exists($username){
    global $db;
    $query = "SELECT email, bio FROM users WHERE username = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    if(count($results)>0){
        return true;
    }

    return false;
}

function get_user_details($username){
    global $db;
    $query = "SELECT email, bio FROM users WHERE username = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();
    return $user;
}

function get_user_activity($username) { 
    global $db;
    $query = "SELECT writes.review_num, reviews.r_text FROM writes JOIN reviews ON writes.review_num = reviews.review_num WHERE writes.username = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $activity = $statement->fetchAll();
    $statement->closeCursor();
    return $activity;
}

?>