<?php
session_start();
?>
<?php require 'connect-db.php'; ?>

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

  <form name="mainForm" action="search_page.php" method="post">   
  <div class="row mb-3 mx-3">
    Username:
    <input type="text" class="form-control" name="username" required />        
  </div>  
  <div class="row mb-3 mx-3">
    <!-- Password:
    <input type="text" class="form-control" name="password" required />    -->     
  </div>
  <div>
  <input type="Submit" value="Sign in">
  </div>
</form>
</body>



</html>