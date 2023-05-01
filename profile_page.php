<?php
session_start();
require 'connect-db.php';
include('navbar.php');

    class User { 
        public $name;
        public $email;
        public $bio;
    
        function __construct($name, $email, $bio) {
            $this->name = $name;
            $this->email = $email;
            $this->bio = $bio;
        }
    
        function update_bio($new_bio) {
            $this->bio = $new_bio;
        }

        function get_bio() {
            return $this->bio;
        }
    }

	if(isset($_SESSION['username'])) { 
        $username = $_SESSION['username'];

		if(check_user_exists($username)){
            $_SESSION['username'] = $username;
            $user_details = get_user_details($_SESSION['username']);

            $activity = get_user_activity($_SESSION['username']);
            if($activity != null) { 
                $_SESSION['review_num'] = $activity;
            }

            $_SESSION['email'] = $user_details->email;
            $_SESSION['bio'] = $user_details->bio;

            $books = get_user_books($_SESSION['username']);

            if($books != null) { 
                $_SESSION['isbn'] = $books;
            } else { 
                echo "Not in existence";
            }
        }

			#echo "Signed in as ".$_SESSION['username'];
	} else {
		header('Location: sign_in.php');
        exit();
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
        $user_details = $statement->fetch();
        $statement->closeCursor();

        if(!$user_details){
            return null;
        }
    
        $user = new User('username', $user_details['email'], $user_details['bio']);
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

        $bookTitles = array();
        foreach ($books as $book) {
            $bookTitles[$book['isbn']] = $book['title'];
        }
        $_SESSION['books'] = $books;
        $_SESSION['bookTitles'] = $bookTitles;

        return $books;
    }

    function update_user_bio($username, $new_bio) {
        global $db;
        $query = "UPDATE users SET bio = :new_bio WHERE username = :username;";
        $statement = $db->prepare($query);
        $statement->bindValue(':new_bio', $new_bio);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $statement->closeCursor();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
        $user_details = get_user_details($_SESSION['username']);
        $user_details->update_bio($_POST['bio']);
        $_SESSION['bio'] = $user_details->get_bio();
        update_user_bio($_SESSION['username'], $_POST['bio']);
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
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="./index_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <form method = "post">
                        <label for = "bio">Bio:</label><br>
                        <textarea id = "bio" name = "bio"><?php echo $_SESSION['bio']?></textarea></br>
                        <input type = "submit" value = "Update Bio">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <strong>User Activity</strong>
    <div class="row">
        <div class="col-lg-8"> 
            <div class="card">
                <div class ="card-body">
                    <p class="card-title">Reviews: <?php echo count($_SESSION['review_num'] ?? [])?></p>
                    <?php foreach($_SESSION['review_num'] ?? [] as $activity): ?>
                        <p class="card-text">Review: <?php echo $activity['r_text']?> </p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <strong>Reading Lists</strong>
    <div class="row">
        <div class="col-lg-8"> 
            <div class ="card">
                <div class ="card-body">
                <!-- <h5 class="card-title">Books Currently Reading</h5>
                    <ul class="list-group"> -->
                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I'm Currently Reading</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php foreach($_SESSION['isbn'] ?? [] as $book): ?>
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

                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I've Read</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php foreach($_SESSION['isbn'] ?? [] as $book): ?>
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

                    <table class="w3-table w3-bordered w3-card-4 left" style="width: 30%">
                        <thead>
                        <tr style="background-color:#B0B0B0">
                            <th>Books I Want to Read</th>         
                        </tr>
                        </thead>
                        <?php $count = 1; ?>
                        <?php foreach($_SESSION['isbn'] ?? [] as $book): ?>
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