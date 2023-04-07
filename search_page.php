<?php session_start(); ?>
<?php require 'connect-db.php'; ?>
<?php require 'filter_functions.php'; ?>

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

 
$search_results = selectAllBooks();
//$search_results = filterByTitle();

if (isset($_POST['filter_options'])) {

    $filter = $_POST['filter_options'];
    echo $filter;
    if ($filter == 'title'){
        $search_results = filterByTitle();
    }   
    if ($filter == 'authors'){
        $search_results = filterByAuthor();
    }
    if ($filter == 'isbn'){
        $search_results = filterByISBN();
    }
    if ($filter == 'language'){
        $search_results = filterByLanguage();
    }
}
else {
    echo "no set";
}

//<?php if(isset($_POST["filter_options"]) && $_POST["filter_options"]  == "Author") echo "selected";
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
                    <form id="search-form" action="search_page.php" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="row no-gutters">
                                    <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                                        <select  class="form-control" name="filter_options">
                                            <option value='title'>Title</option>
                                            <option value='authors'>Author(s)</option>
                                            <option value='isbn'>ISBN</option>
                                            <option value='language'>Language Code</option>
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
            <th>Title</th>      
            <th>Author(s)</th>         
            <th>Average Rating</th> 
            <th>Details?</th>        
            <th>Rate?</th>  
         </tr>
         </thead>
         <?php foreach ($search_results as $item): ?>
            <tr>
               <td><?php echo $item['title']; ?></td>
               <td><?php echo $item['authors']; ?></td>        
               <td><?php echo $item['average_rating']; ?></td>
               <td>
                  <form action="search_page.php" method="post">
                     <input type="submit" name="actionBtn" value="Details" class="btn btn-dark"/>
                     <input type="hidden" name="book_to_inspect" value="<?php echo $item['isbn']; ?>"/>
                  </form>     
               </td>
               <td>
                  <form action="search_page.php" method="post">
                     <input type="submit" name="actionBtn" value="Rate" class="btn btn-danger"/>
                     <input type="hidden" name="book_to_rate" value="<?php echo $item['isbn']; ?>"/>
                  </form>     
               </td>     
            </tr>
         <?php endforeach; ?>
         </table>
    </div>  

</div>
</html>