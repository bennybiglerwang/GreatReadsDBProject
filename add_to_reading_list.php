<?php
session_start();
require 'connect-db.php';

$_SESSION['POST'] = $_POST;

if (!isset($_SESSION['username'])) {
    header('Location: sign_in.php');
    exit();
}

if(isset($_POST['ISBN']) && isset($_POST['status'])){
    global $db;
    $stmt = $db->prepare('INSERT INTO set_status (username, isbn, status) VALUES (:username, :isbn, :status)
        ON DUPLICATE KEY UPDATE status = :status');

    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->bindParam(':isbn', $_POST['ISBN']);
    $stmt->bindParam(':status', $_POST['status']);

    $stmt->execute();

    header('Location: book_page.php');
    exit();
} else { 
    echo "This ain't working you bozo";
}
?>

