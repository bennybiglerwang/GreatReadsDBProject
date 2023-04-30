<?php

function selectAllUsers(){

    global $db;
    
    $query="select * from users";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

function filterByUsername(){

    global $db;

    $query = "SELECT * FROM users
            WHERE (`username` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}

function filterByEmail(){

    global $db;

    $query = "SELECT * FROM users
            WHERE (`email` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}
?>