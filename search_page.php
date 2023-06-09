<?php session_start(); ?>
<?php require 'connect-db.php'; ?>
<?php require 'book_filter_functions.php'; ?>
<?php include('navbar.php'); ?>

<?php
if(isset($_POST['username'])){
   if(check_user_exists($_POST['username'])){
      $_SESSION['username'] = $_POST['username'];
      echo "Welcome ".$_SESSION['username']."!<br />";
   }
   else{
      echo "User does not exist, try again! <br />";
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

if(isset($_POST['search_query'])){
	$search_query = $_POST['search_query'];
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
	$_POST['search_results'] = $search_results;
}
else {
    //echo "no set";
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
                                        <input type="text" placeholder="Search..." class="form-control"name="search_query" required>
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
         <?php //foreach ($search_results as $item):
				$max_pages = ceil(count($search_results)/100);
				if(!isset($_POST['page_increment'])){
					$_POST['page'] = 0;
					$_POST['page_increment'] = 0;
				}
				//echo $_POST['page'];
				//echo $_POST['page_increment'];
				if(isset($_POST['next_page'])){
					$_POST['page'] = $_POST['page'] + $_POST['page_increment'];
					if($_POST['page'] > $max_pages-1){
						$_POST['page'] = $_POST['page'] - $_POST['page_increment'];
					}
				}
				if(isset($_POST['previous_page'])){
					$_POST['page'] = $_POST['page'] - $_POST['page_increment'];
					if($_POST['page'] < 0){
						$_POST['page'] = $_POST['page'] + $_POST['page_increment'];
					}
				}
				for ($x=0; $x <= 100; $x++){
					if(!isset($search_results[$_POST['page'] * 100 + $x])){
						break;
					}
					$item = $search_results[$_POST['page'] * 100 + $x];
				?>
            <tr>
               <td><?php echo $item['title']; ?></td>
               <td><?php echo $item['authors']; ?></td>        
               <td><?php echo $item['average_rating']; ?></td>
               <td>
                <form action="book_page.php" method="post">
                     <input type="submit" name="actionBtn" value="Details" class="btn btn-dark"/>
                     <input type="hidden" name="ISBN" value="<?php echo $item['isbn']; ?>"/>
					 <input type="hidden" name="title" value="<?php echo $item['title']; ?>"/>
                     <input type="hidden" name="authors" value="<?php echo $item['authors']; ?>"/>
                </form>     
               </td>
               <td>
                  <form action="search_page.php" method="post">
                     <input type="submit" name="actionBtn" value="Rate" class="btn btn-danger"/>
                     <input type="hidden" name="book_to_rate" value="<?php echo $item['isbn']; ?>"/>
                  </form>     
               </td>     
            </tr>
				<?php } ?>
		 
			 <tbody>
				<tr>
					<td width="30%" align="left">
						<form action="search_page.php" method="post">
							<input type="submit" value="Previous" name="previous_page" />
							<input type="hidden" name="page_increment" value=1 />
							<input type="hidden" name="page" value=<?php echo $_POST['page']; ?> />
							<?php if(isset($_POST['search_query']) and isset($_POST['filter_options'])){ ?>
								<input type="hidden" name="search_query" = value=<?php echo $_POST['search_query']; ?> />
								<input type="hidden" name="filter_options" = value=<?php echo $_POST['filter_options']; ?> />
							<?php } ?>
					</td>
					<td>
						<p> Page <?php echo $_POST['page'] + 1; ?> out of <?php echo $max_pages; ?> </p>
					</td>
					<td width="30%" align="right">
						<form action="search_page.php" method="post">
							<input type="submit" value= "Next" name="next_page" />
							<input type="hidden" name="page_increment" value=1 />
							<input type="hidden" name="page" value=<?php echo $_POST['page']; ?> />
							<?php if(isset($_POST['search_query']) and isset($_POST['filter_options'])){ ?>
								<input type="hidden" name="search_query" = value=<?php echo $_POST['search_query']; ?> />
								<input type="hidden" name="filter_options" = value=<?php echo $_POST['filter_options']; ?> />
							<?php } ?>
					</td>
				</tr>
			 </tbody>
		 
         </table>
    </div>  

</div>
</html>