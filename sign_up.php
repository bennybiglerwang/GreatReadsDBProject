<?php require 'connect-db.php'; ?>
<?php include('navbar.php'); ?>

<!DOCTYPE html>

<html>
<head>
<title> Sign up </title>

<link href="./index_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="book_page.css">
</head>

<?php
session_start();
?>

<body>
<div class="container">
  <h1>Sign in</h1>  

  <form name="mainForm" action="sign_up.php" method="post">   
  <div class="row mb-3 mx-3">
    Create Username:
    <input type="text" class="form-control" name="username" required />  
    </div>  
    <div class="row mb-3 mx-3">
	First Name:
	<input type="text" class="form-control" name="first_name" required />  
    </div>
    <div class="row mb-3 mx-3">
	Last Name:
	<input type="text" class="form-control" name="last_name" required />  
    </div>
	Email:
	<input type="text" class="form-control" name="email" required />  
    </div>
    <div class="row mb-3 mx-3">
	Create Password:
	<input type="text" class="form-control" name="password" required />  
    </div>
    <div class="row mb-3 mx-3">
  <div>
  <input type="Submit" value="Sign in">
  </div>
</form>
</div>
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

function create_user($username, $email, $pwd, $fname, $lname){
	if(check_user_exists($username)){
		echo "<p> That username is taken, try again! </p> ";
	} else {
		global $db;
		$query = "
		INSERT INTO users 
		VALUES (:username, :email, :bio, :first_name, :last_name, :password);";
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $username);
		$statement->bindValue(':email', $email);
		$statement->bindValue(':first_name', $fname);
		$statement->bindValue(':last_name', $lname);
		$hashed_pwd = password_hash($pwd, PASSWORD_BCRYPT);
		$statement->bindValue(':password', $hashed_pwd);
		$statement->bindValue(':bio', "");
		$statement->execute();
		
		//$query = "
		// (MAY OR MAY NOT WANT TO ADD THEM AS USERS TO THE DATABASE AS WELL)
		//";
		//$statement = $db->prepare($query);
		//$statement->execute();
		
		header("Location: ".'search_page.php');
		
		$statement->closeCursor();
	}
}

if(isset($_POST['username']) and isset($_POST['password']) and isset($_POST['first_name']) and isset($_POST['last_name'])){
	create_user($_POST['username'], $_POST['email'], $_POST['password'], $_POST['first_name'], $_POST['last_name']);
}
?>

</html>