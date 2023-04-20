<?php
session_start();
?>
<?php require 'connect-db.php'; ?>
<?php include('navbar.php'); ?>

<!DOCTYPE html>
<html>

<head>
<title> Sign in </title>

<link href="./index_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="book_page.css">
</head>

<body>
<div class="container">
  <h1>Sign in</h1>  

  <form name="mainForm" action="sign_in.php" method="post">   
  <div class="row mb-3 mx-3">
    Username:
    <input type="text" class="form-control" name="username" required />  
  </div>  
  <div class="row mb-3 mx-3">
	Password:
	<input type="text" class="form-control" name="password" required />  
  </div>
  <div>
  <input type="Submit" value="Sign in">
  </div>
</form>
</div>
<p> Or <a href='sign_up.php'> sign up! </a> </p>
<?php
function check_user_exists($username){
   global $db;
   $query = "
   select * from users
   where username = :username;";
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
?>
<?php 
function getHashedPassword($user){
	global $db;
	$query = "
		SELECT hashed_password
		FROM users
		WHERE username=:username;";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $user);
	$statement->execute();
	$results = $statement->fetchAll();
	$statement->closeCursor();
	return $results;
}


function authenticate()
{
   global $mainpage;

   // Assume there exists a hashed password for a user (username='demo', password='demo') 
   // in a database or file and we've retrieved and assigned it to a $hash variable 
   
   //TO DO: Write PHP function to retrieve hashed password.
   
    if(isset($_POST['username'])){
		if(check_user_exists($_POST['username'])){
			$results = getHashedPassword($_POST['username']);   // hash for 'demo'
			$x = array_shift($results);
			$hash = array_shift($x);
		} else {
			echo "Username does not exist!";
			return;
		}
   }
   
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      // htmlspecialchars() stops script tags from being able to be executed and renders them as plaintext
      $pwd = htmlspecialchars($_POST['password']);      
    
      if (password_verify($pwd, $hash))
      {  
         // successfully login, redirect a user to the main page
		 $_SESSION['username'] = $_POST['username'];
         header("Location: ".$mainpage);
         
      }
      else       
         echo "<span class='msg'>Username and password do not match our record</span> <br/>";
   }	
}
$mainpage = "search_page.php";   
authenticate();
?>
</body>



</html>