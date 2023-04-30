<?php
session_start();
require 'connect-db.php';

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $recs = get_user_recs($username);
    if($recs == null) { 
        echo 'You do not have any recommendations.';
    }

} else echo 'You must be logged in to view your recommendations.';

var_dump($recs);

function get_user_recs($username){
    global $db;
    $query = "SELECT * FROM recommends WHERE username1 = :username;";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="container">
    <strong>Your Recommendations</strong>
    <?php foreach ($recs as $item): ?>
            <tr>
               <td><?php echo $item['book_isbn']; ?></td>
               <td><?php echo $item[2]; ?></td>        
            </tr>
         <?php endforeach; ?>
</body>
</html>