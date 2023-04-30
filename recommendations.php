<?php
session_start();
require 'connect-db.php';
include('navbar.php');

$recs = NULL;

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $recs = get_user_recs($username);
    if($recs == null) { 
        echo 'You do not have any recommendations.';
    }

} else echo 'You must be logged in to view your recommendations.';

//var_dump($recs);

function get_user_recs($username){
    global $db;
    $query = "SELECT * FROM recommends WHERE username2 = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
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
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="book_link.css"/>
</head> 

<html>  
<div class="container">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="search-filter.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <div class="row justify-content-center">  
            <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
                <thead>
                <tr style="background-color:#B0B0B0">
                    <th>Book</th>      
                    <th>From</th>    
                    <th></th>       
                </tr>
                </thead>
                <strong>Your Recommendations</strong>
                <?php if($recs!=NULL) { ?>
                    <?php $count = 1; ?>
                    <?php foreach ($recs as $item): ?>
                        <form method="POST" action="book_page.php" class="inline" id="book_link<?php echo $count; ?>">
                        <tr>
                            <?php $book_title = get_book_title($item['book_isbn']); ?>
                            <?php $book_authors = get_book_authors($item['book_isbn']); ?>
                            <td>
                                <input type="hidden" name="title" value="<?php echo $book_title[0]['title']; ?>" form="book_link<?php echo $count; ?>">
                                <input type="hidden" name="ISBN" value="<?php echo $item['book_isbn'] ?>" form="book_link<?php echo $count; ?>">
                                <input type="hidden" name="authors" value="<?php echo $book_authors[0]['authors']; ?>"  form="book_link<?php echo $count; ?>">
                                <button type="submit" name="submitparam" class="link-button" form="book_link<?php echo $count; ?>">
                                    <?php echo $book_title[0]['title']; ?>
                                </button>
                            </td>
                            <td>
                                <?php echo $item['username1']; ?>
                            </td>   
                        </tr>
                        </form>
                        <?php $count = $count + 1; ?>
                    <?php endforeach; ?>
                <?php } ?>
            </table>
    </div>  

</div>
</html>
