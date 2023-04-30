<?php
function selectAllBooks(){

    global $db;
    
    $query="select * from books";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

// $filter_options = array(
//     'title' => 'Title',
//     'author' => 'author',
//     'isbn' => 'isbn',
//     'language' => 'language'

// )
    
function filterByTitle(){

    global $db;

    $query = "SELECT * FROM books
            WHERE (`title` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}

function filterByAuthor(){

    global $db;

    $query = "SELECT * FROM books
            WHERE (`authors` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}

function filterByISBN(){

    global $db;

    $query = "SELECT * FROM books
            WHERE (`ISBN` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}

function filterByLanguage(){

    global $db;

    $query = "SELECT * FROM books
            WHERE (`language_code` LIKE '%".$_POST['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}

?>