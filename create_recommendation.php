<?php
session_start();
require 'connect-db.php';
include 'friends_functions.php';
include('navbar.php');


if(isset($_POST['book_to_rec'])){
    $rec_isbn = $_POST['book_to_rec'];
} else {
    echo "not set";
}

$users = selectAllUsers();

function selectAllUsers(){
    global $db;
    
    $query= "select username from users";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
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

function recommendBook($sender, $receiver, $book_isbn){
    global $db;
    $query = "insert into recommends values (:sender, :receiver, :book_isbn);";
    $statement = $db->prepare($query);
    $statement->bindValue(':sender', $sender);
    $statement->bindValue(':receiver', $receiver);
    $statement->bindValue(':book_isbn', $book_isbn);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;

}

function checkRecs($sender, $receiver, $book_isbn){
    global $db;
    $query = "select * from recommends where username1 = :sender and username2 = :receiver and book_isbn = :book_isbn";
    $statement = $db->prepare($query);
    $statement->bindValue(':sender', $sender);
    $statement->bindValue(':receiver', $receiver);
    $statement->bindValue(':book_isbn', $book_isbn);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Send')){
 
       recommendBook($_SESSION['username'], $_POST['receiver_username'], $_POST['book_to_rec_isbn']);
    }
}

//$friends = [];


//var_dump($friends);
?>

<html>  
<div class="container">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="search-filter.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
    <div class="row justify-content-center"> 
    <?php $book_title = get_book($rec_isbn); ?>
    <strong>Recommend <?php echo $book_title[0]['title'] ?> to a friend!</strong>
      <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
         <thead>
         <tr style="background-color:#B0B0B0">
            <th>Friend</th> 
            <th>Send Rec?</th>        
         </tr>
         </thead>
         <?php foreach($users as $item): ?>
            <?php if(checkFriendshipStatus($item['username'], $_SESSION['username']) == 'friends'){ ?>
            <tr>
               <td><?php echo $item['username']; ?></td>
               <td>
               <?php if(checkRecs($_SESSION['username'], $item['username'], $rec_isbn) != NULL) { ?> 
                    <form action="create_recommendation.php" method="post">
                    <input type="submit" name="actionBtn" value="Sent" class="btn btn-dark" disabled/>
                    </form>     
                <?php } else {?>
                  <form action="create_recommendation.php" method="post">
                     <input type="submit" name="actionBtn" value="Send" class="btn btn-dark"/>
                     <input type="hidden" name="receiver_username" value="<?php echo $item['username'] ?>"/>
                     <input type="hidden" name="book_to_rec_isbn" value="<?php echo $rec_isbn; ?>"/>
                     <input type="hidden" name="book_to_rec" value="<?php echo $rec_isbn; ?>"/>
                  </form>  
                  <?php } ?>   
               </td>
            </tr>
            <?php }?>
         <?php endforeach; ?>
         </table>
    </div>  

</div>
</html>