<?php session_start(); ?>
<?php require 'connect-db.php'; ?>
<?php require 'user_filter_functions.php'; ?>
<?php include('navbar.php'); ?>

<?php
if(isset($_POST['username'])){
   if(check_user_exists($_POST['username'])){
      $_SESSION['username'] = $_POST['username'];
      echo "Signed in as ".$_SESSION['username'];
   }
   else{
      echo "User does not exist, try again!";
   }
}

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

 
$user_search_results = selectAllUsers();

if (isset($_POST['filter_options'])) {

    $filter = $_POST['filter_options'];
    //echo $filter;
    if ($filter == 'username'){
        $user_search_results = filterByUsername();
    }   
    if ($filter == 'email'){
        $user_search_results = filterByEmail();
    }
}
else {
    //echo "no set";
}
?>









<!DOCTYPE html>
<html>

<div class="container">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="search-filter.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
<div class="container">
    <div class="row">
        <div class="col-lg-12 card-margin">
            <div class="card search-form">
                <div class="card-body p-0">
                    <form id="search-form" action="search_users_page.php" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="row no-gutters">
                                    <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                                        <select  class="form-control" name="filter_options">
                                            <option value='username'>Username</option>
                                            <option value='email'>Email</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12 p-0">
                                        <input type="text" placeholder="Search..." class="form-control"name="search_query">
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-sm-12 p-0">
                                        <button type="submit" class="btn btn-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</html>





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
            <th>Username</th>      
            <th>Email</th>    
            <th></th>     
            <th></th>     
         </tr>
         </thead>
         <?php foreach ($user_search_results as $item): ?>
            <tr>
               <td><?php echo $item['username']; ?></td>
               <td><?php echo $item['email']; ?></td>    
               <td>
                  <form action="user_page.php" method="post">
                     <input type="submit" name="actionBtn" value="Profile" class="btn btn-dark"/>
                     <input type="hidden" name="username" value="<?php echo $item['username']; ?>"/>
					 <input type="hidden" name="email" value="<?php echo $item['email']; ?>"/>
                     <input type="hidden" name="bio" value="<?php echo $item['bio']; ?>"/>

                  </form>     
               </td>
            </tr>
         <?php endforeach; ?>
         </table>
    </div>  

</div>
</html>