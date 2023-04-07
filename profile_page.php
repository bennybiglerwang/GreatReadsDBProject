<?php
session_start();
require 'connect-db.php';

	if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];

		if(check_user_exists($username)){
            $_SESSION['username'] = $username;
            $user = get_user_details($_SESSION['username']);

            $activity = get_user_activity($_SESSION['username']);
            if($activity != null) { 
                $_SESSION['review_num'] = $activity;
            }

            $_SESSION['email'] = $user['email'];
            $_SESSION['bio'] = $user['bio'];

            

			echo "Signed in as ".$_SESSION['username'];
		} else{
			echo "User does not exist, try again!";
		}
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
                    <p class="card-title">User: <?php echo $_SESSION['username']?></p>
                    <p class="card-text">Email: <?php echo $_SESSION['email']?></p>
                    <p class="card-text">Bio: <?php echo $_SESSION['bio']?></p>
                </div>
            </div>
        </div>
    </div>
    <strong>User Activity</strong>
    <div class="row">
        <div class="col-lg-8"> 
            <div claass="card">
                <div class ="card-body">
                    <p class="card-title">Reviews: <?php echo count($_SESSION['review_num'])?></p>
                    <?php foreach($_SESSION['review_num'] as $activity): ?>
                        <p class="card-text">Review: <?php echo $activity['r_text']?> (Review number: <?php echo $activity['review_num']?>)</p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>