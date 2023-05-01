<?php session_start(); ?>
<?php
	if(isset($_SESSION['username'])){
		#echo "Signed in as ".$_SESSION['username'];
	} else { 
        header('Location: sign_in.php');
        exit();
  }
  
?>
<?php require 'connect-db.php'; ?>
<?php require 'friends_functions.php'; ?>
<?php require('navbar.php'); ?>

<?php

// echo $_SESSION['username'];
// echo $_POST['username'];
// echo checkFriendshipStatus($_SESSION['username'], $_POST['username']);

function get_user_books($username) {
    global $db;

    $query = "SELECT s.username, b.title, s.isbn, s.status FROM set_status s
    INNER JOIN books b ON s.isbn = b.isbn
    WHERE s.username = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $books = $statement->fetchAll();
    $statement->closeCursor();

    unset($_SESSION['books']);
    unset($_SESSION['bookTitles']);

    $bookTitles = array();
    foreach ($books as $book) {
        $bookTitles[$book['isbn']] = $book['title'];
    }
   
    $_SESSION['books'] = $books;
    $_SESSION['bookTitles'] = $bookTitles;

    return $books;
}

if(isset($_POST['username'])){
    $username = $_POST['username'];
    if(isset($_POST['email'])){
         $email = $_POST['email'];
    }
    else if(!isset($_POST['email'])){
        $email = 'This user does not have an email';
    }


    if(isset($_POST['bio'])){
        $bio = $_POST['bio'];
    }
    else if(!isset($_POST['bio'])){
        $bio = 'This user does not have a bio';
    }
    if(check_user_exists($username)){
        $user = get_user_details($username);
        $email = $user['email'] ?? 'This user does not have an email';
        $bio = $user['bio'] ?? 'This user does not have a bio';

        $activity = get_user_activity($username);
        if($activity != null) { 
            $_POST['review_num'] = $activity;
        }

        $books = get_user_books($username);    
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

    function get_book_title($book_isbn){
        global $db;
        $query = "SELECT title FROM books WHERE isbn = :book_isbn;";
        $statement = $db->prepare($query);
        $statement->bindValue(':book_isbn', $book_isbn);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    }
    
    function get_book_authors($book_isbn){
        global $db;
        $query = "SELECT authors FROM books WHERE isbn = :book_isbn;";
        $statement = $db->prepare($query);
        $statement->bindValue(':book_isbn', $book_isbn);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="./index_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="book_link.css"/>

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
    <?php if(isset($_SESSION['username'])){ ?>
    <form action="user_page.php" method="post">
        <?php if(checkFriendshipStatus($_SESSION['username'], $username) == "self"){ ?>
            <input type="submit" name="actionBtn" value="self" disabled />
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "not friends"){ ?>
            <input type="submit" name="actionBtn" value="Send a Friend Request" />
            <input type="hidden" name="friend_to_request" value="<?php echo $username; ?>"/>
            <input type="hidden" name="username" value="<?php echo $username; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <input type="hidden" name="bio" value="<?php echo $bio; ?>"/>
        <?php } elseif(checkFriendshipStatus($_SESSION['username'], $username) == "friends"){ ?>
            <input type="submit" name="actionBtn" value="Unfriend" />
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
            <?php } ?>
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
                        <p class="card-text">Review: <?php echo $activity['r_text']?> </p>
                    <?php endforeach; } else {echo "This user has no reviews";}?>
                </div>
            </div>
        </div>
    </div>

    <strong>Reading Lists</strong>
    <div class="row">
        <div class="col-lg-8"> 
            <div class ="card">
                <div class ="card-body">
                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I'm Currently Reading</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php $books = get_user_books($username); ?>
                        <?php foreach($books as $book): ?>
                        <?php if ($book['status'] == 'currently-reading' && isset($book['isbn'])): ?>
                                <form method="POST" action="book_page.php" class="inline" id="book_link<?php echo $count;?>">
                                    <tr>
                                        <?php $book_title = get_book_title($book['isbn']); ?>
                                        <?php $book_authors = get_book_authors($book['isbn']); ?>
                                        <td>
                                            <input type="hidden" name="title" value="<?php echo $book_title[0]['title']; ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="ISBN" value="<?php echo $book['isbn'] ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="authors" value="<?php echo $book_authors[0]['authors']; ?>"  form="book_link<?php echo $count; ?>">
                                            <button type="submit" name="submitparam" class="link-button" form="book_link<?php echo $count; ?>">
                                                <?php echo $book_title[0]['title']; ?>
                                            </button>
                                        </td>
                                    </tr>
                                </form>


                            <?php endif; ?>
                        <?php $count = $count + 1; ?>
                        <?php endforeach; ?>

                    <!-- </ul>
                    <h5 class="card-title">Books Read</h5>
                    <ul class="list-group"> -->
                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I Want To Read</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php foreach($books as $book): ?>
                            <?php if ($book['status'] == 'to-read' && isset($book['isbn'])): ?>
                                <form method="POST" action="book_page.php" class="inline" id="book_link<?php echo $count;?>">
                                    <tr>
                                        <?php $book_title = get_book_title($book['isbn']); ?>
                                        <?php $book_authors = get_book_authors($book['isbn']); ?>
                                        <td>
                                            <input type="hidden" name="title" value="<?php echo $book_title[0]['title']; ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="ISBN" value="<?php echo $book['isbn'] ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="authors" value="<?php echo $book_authors[0]['authors']; ?>"  form="book_link<?php echo $count; ?>">
                                            <button type="submit" name="submitparam" class="link-button" form="book_link<?php echo $count; ?>">
                                                <?php echo $book_title[0]['title']; ?>
                                            </button>
                                        </td>
                                    </tr>
                                </form>


                            <?php endif; ?>
                        <?php $count = $count + 1; ?>
                        <?php endforeach; ?>
                      
                    <!-- </ul>
                    <h5 class="card-title">Books Will Read</h5>
                    <ul class="list-group"> -->


                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I've Read</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php foreach($books as $book): ?>
                            <?php if ($book['status'] == 'read' && isset($book['isbn'])): ?>
                                <form method="POST" action="book_page.php" class="inline" id="book_link<?php echo $count;?>">
                                    <tr>
                                        <?php $book_title = get_book_title($book['isbn']); ?>
                                        <?php $book_authors = get_book_authors($book['isbn']); ?>
                                        <td>
                                            <input type="hidden" name="title" value="<?php echo $book_title[0]['title']; ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="ISBN" value="<?php echo $book['isbn'] ?>" form="book_link<?php echo $count; ?>">
                                            <input type="hidden" name="authors" value="<?php echo $book_authors[0]['authors']; ?>"  form="book_link<?php echo $count; ?>">
                                            <button type="submit" name="submitparam" class="link-button" form="book_link<?php echo $count; ?>">
                                                <?php echo $book_title[0]['title']; ?>
                                            </button>
                                        </td>
                                    </tr>
                                </form>


                            <?php endif; ?>
                        <?php $count = $count + 1; ?>
                        <?php endforeach; ?>

                    </ul>
                   
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


