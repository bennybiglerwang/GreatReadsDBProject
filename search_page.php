<?php session_start(); ?>
<?php require 'connect-db.php'; ?>
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
?>
<!DOCTYPE html>
<html>

<div class="container">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="search-filter.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <div class="row searchFilter" >
     <div class="col-sm-12" >
      <div class="input-group" >
       <input id="table_filter" type="text" class="form-control" aria-label="Text input with segmented button dropdown" >
       <div class="input-group-btn" >
        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span class="label-icon" >Category</span> <span class="caret" >&nbsp;</span></button>
        <div class="dropdown-menu dropdown-menu-right" >
           <ul class="category_filters" >
            <li >
             <input class="cat_type category-input" data-label="All" id="all" value="" name="radios" type="radio" ><label for="all" >All</label>
            </li>
            <li >
             <input type="radio" name="radios" id="Design" value="Design" ><label class="category-label" for="Design" >Book</label>
            </li>
            <li >
             <input type="radio" name="radios" id="Marketing" value="Marketing" ><label class="category-label" for="Marketing" >Author</label>
            </li>
            <li >
             <input type="radio" name="radios" id="Programming" value="Programming" ><label class="category-label" for="Programming" >ISBN</label>
            </li>
            <li >
             <input type="radio" name="radios" id="Sales" value="Sales" ><label class="category-label" for="Sales" >Language</label>
            </li>
            <li >
             <input type="radio" name="radios" id="Support" value="Support" ><label class="category-label" for="Support" >Publisher</label>
            </li>
           </ul>
        </div>
        <button id="searchBtn" type="button" class="btn btn-secondary btn-search" ><span class="glyphicon glyphicon-search" >&nbsp;</span> <span class="label-icon" >Search</span></button>
       </div>
      </div>
     </div>
  </div>
</div>
</html>
