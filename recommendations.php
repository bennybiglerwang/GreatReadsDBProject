<?php
session_start();
require 'connect-db.php';
include('navbar.php');

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

function get_book($book_isbn){
    global $db;
    $query = "SELECT title FROM books WHERE isbn = :book_isbn;";
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
            <?php foreach ($recs as $item): ?>
                <tr>
                <?php $book_title = get_book($item['book_isbn']); ?>
                <!--<?php var_dump($book_title); ?>-->
                <td><?php echo $book_title[0]['title']; ?></td>
                <td><?php echo $item['username1']; ?></td>
                </tr>
            <?php endforeach; ?>
         </table>
    </div>  

</div>
</html>