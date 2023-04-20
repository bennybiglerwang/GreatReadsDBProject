<?php session_start(); ?>
<?php
	if(isset($_SESSION['username'])){
		echo "Signed in as ".$_SESSION['username'];
	}
?>
<?php require 'connect-db.php'; ?>
<?php include('navbar.php'); ?>

<?php

// echo $_SESSION['username'];
// echo $_POST['username'];
// echo checkFriendshipStatus($_SESSION['username'], $_POST['username']);


if(isset($_POST['username'])){
    $username = $_POST['username'];

    if(check_user_exists($username)){
        $_POST['username'] = $username;
        $user = get_user_details($_POST['username']);

        $activity = get_user_activity($_POST['username']);
        if($activity != null) { 
            $_POST['review_num'] = $activity;
        }

        $_POST['email'] = $user['email'];
        $_POST['bio'] = $user['bio'];

        
    }
}

// $username1 = $_SESSION['username'];
// $username2 = $_POST['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'friend')){
 
       addFriend(NULL, $_SESSION['username'], $_POST['friend_to_request']);
  
    } elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'accept request')){
 
        addFriend(NULL, $_SESSION['username'], $_POST['friend_to_accept']);
  
    }  elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'unfriend')){
 
        deleteFriend($_SESSION['username'], $_POST['friend_to_unfriend']);
  
    } 

}

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="container">
    <strong>Personal Information</strong>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">User: <?php echo $_POST['username']?></p>
                    <p class="card-text">Email: <?php echo $_POST['email']?></p>
                    <p class="card-text">Bio: <?php echo $_POST['bio']?></p>
                </div>
            </div>  
        </div>
    </div>
    <form action="user_page.php" method="post">
        <?php if(checkFriendshipStatus($_SESSION['username'], $_POST['username']) == "self"){ ?>
            <input type="submit" name="actionBtn" value="self" disabled />
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $_POST['username']) == "not friends"){ ?>
            <input type="submit" name="actionBtn" value="friend" />
            <input type="hidden" name="friend_to_request" value="<?php echo $_POST['username']; ?>"/>
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $_POST['username']) == "friends"){ ?>
            <input type="submit" name="actionBtn" value="unfriend" />
            <input type="hidden" name="friend_to_unfriend" value="<?php echo $_POST['username']; ?>"/>
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $_POST['username']) == "request sent"){ ?>
            <input type="submit" name="actionBtn" value="request sent" disabled />
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $_POST['username']) == "request received"){ ?>
            <input type="submit" name="actionBtn" value="accept request" />
            <input type="hidden" name="friend_to_accept" value="<?php echo $_POST['username']; ?>"/>
        <?php } ?>
    </form>     
    <strong>User Activity</strong>
    <div class="row">
        <div class="col-lg-8"> 
            <div claass="card">
                <div class ="card-body">
                    <p class="card-title">Reviews: <?php if(count($activity)>0) {echo count($_POST['review_num']);?></p>
                    <?php foreach($_POST['review_num'] as $activity): ?>
                        <p class="card-text">Review: <?php echo $activity['r_text']?> (Review number: <?php echo $activity['review_num']?>)</p>
                    <?php endforeach; } else {echo "This user has no reviews";}?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<!-- 
<input type="submit" name="actionBtn" value=<?php echo checkFriendshipStatus($_SESSION['username'], $_POST['username'])?> class="btn btn-dark"/>
        <input type="hidden" name="username_to_friend" value="<?php echo $item['username']; ?>"/>
    </form>      -->
