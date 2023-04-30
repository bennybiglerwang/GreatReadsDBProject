<?php session_start(); ?>
<?php
	if(isset($_SESSION['username'])){
		echo "Signed in as ".$_SESSION['username'];
	}
?>
<?php require 'connect-db.php'; ?>
<?php require 'friends_functions.php'; ?>
<?php require('navbar.php'); ?>

<?php

// echo $_SESSION['username'];
// echo $_POST['username'];
// echo checkFriendshipStatus($_SESSION['username'], $_POST['username']);


if(isset($_POST['username'])){
    $username = $_POST['username'];
    if(isset($_POST['email'])){
         $email = $_POST['email'];
    }
    else if(!isset($_POST['email'])){
        $email = 'no email';
    }


    if(isset($_POST['bio'])){
        $bio = $_POST['bio'];
    }
    else if(!isset($_POST['bio'])){
        $bio = 'no bio';
    }
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
} else echo 'user not set';

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
                    <p class="card-title">User: <?php echo $username?></p>
                    <p class="card-text">Email: <?php echo $email?></p>
                    <p class="card-text">Bio: <?php echo $bio?></p>
                </div>
            </div>  
        </div>
    </div>
    <form action="user_page.php" method="post">
        <?php if(checkFriendshipStatus($_SESSION['username'], $username) == "self"){ ?>
            <input type="submit" name="actionBtn" value="self" disabled />
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "not friends"){ ?>
            <input type="submit" name="actionBtn" value="friend" />
            <input type="hidden" name="friend_to_request" value="<?php echo $username; ?>"/>
            <input type="hidden" name="username" value="<?php echo $username; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="hidden" name="bio" value="<?php echo $bio; ?>"/>
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "friends"){ ?>
            <input type="submit" name="actionBtn" value="unfriend" />
            <input type="hidden" name="friend_to_unfriend" value="<?php echo $_POST['username']; ?>"/>
            <input type="hidden" name="username" value="<?php echo $username; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="hidden" name="bio" value="<?php echo $bio; ?>"/>
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "request sent"){ ?>
            <input type="submit" name="actionBtn" value="request sent" disabled />
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "request received"){ ?>
            <input type="submit" name="actionBtn" value="accept request" />
            <input type="hidden" name="friend_to_accept" value="<?php echo $username; ?>"/>
            <input type="hidden" name="username" value="<?php echo $username; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="hidden" name="bio" value="<?php echo $bio; ?>"/>
    </form>     
    <?php } ?>
    <br></br>
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
