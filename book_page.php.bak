<?php session_start(); ?>
<?php
	if(isset($_SESSION['username'])){
		echo "Signed in as ".$_SESSION['username'];
	}
?>
<?php require 'connect-db.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<title>
		<?php 
		if( isset($_POST['title']) ){
			echo $_POST['title'];
		} else {
			echo "Untitled book.";
		}
		?>
		</title>
		
		<link href="./index_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
	</head>
	
	<style>
	div.solid {border-style: solid;}
	div.dotted {border-style: dotted;}
	</style>
	
	<body>
		<h1>
		<?php 
		if(isset($_POST['title'])){
			echo $_POST['title'];
		} else {
			echo "Untitled Book";
		}
		?>
		</h1>
		<h5>
		<?php 
		if(isset($_POST['authors'])){
			echo "by ".$_POST['authors'];
		} else {
			echo "no authors";
		}
		?>
		</h5>
		<p>
		<?php 
		if(isset($_POST['average_rating'])){
			echo "GreatReads Rating: ".$_POST['avg_star_rating']." out of 5 stars.";
		} else {
			echo "no ratings yet";
		}
		?>
		</p>
		
		<div class="container">
		  <h1> Join the discussion! </h1>  

		  <form name="review_form" action="book_page.php" method="post">   
		  <div class="row mb-3 mx-3">
			<p> Review <i><?php echo " ".$_POST['title']; ?></i>: </p>
			<textarea rows="6" cols="70" name="r_text" maxlength="1000" required></textarea>      
		  </div>  
		  <div class="row" style="position:relative;padding-bottom:15px;" >
			<input type="radio" class="form-control" name="star_rating" value="1" style="position:absolute; left:20px; z-index:950; opacity:0 " required />
			<input type="radio" class="form-control" name="star_rating" value="2" style="position:absolute; left:40px; z-index:950; opacity:0" required />
			<input type="radio" class="form-control" name="star_rating" value="3" style="position:absolute; left:60px; z-index:950; opacity:0" required />
			<input type="radio" class="form-control" name="star_rating" value="4" style="position:absolute; left:80px; z-index:950; opacity:0" required />
			<input type="radio" class="form-control" name="star_rating" value="5" style="position:absolute; left:100px; z-index:950; opacity:0" required />
			<input type="hidden" name="ISBN" value="<?php echo $_POST['ISBN']; ?>">
			<input type="hidden" name="title" value="<?php echo $_POST['title']; ?>">
			<input type="hidden" name="authors" value="<?php echo $_POST['authors']; ?>">
			<span class="fa fa-star" style="position:absolute;left:20px"></span>
			<span class="fa fa-star" style="position:absolute;left:40px"></span>
			<span class="fa fa-star" style="position:absolute;left:60px"></span>
			<span class="fa fa-star" style="position:absolute;left:80px"></span>
			<span class="fa fa-star" style="position:absolute;left:100px"></span>
		  </div>
		  <div style="position:relative">
		  <input type="submit" value="Review">
		  </div>
		</form>
		
		<?php 
		if(isset($_POST['r_text']) and isset($_SESSION['username'])){
			try{
				post_review($_POST['ISBN'], $_SESSION['username'], $_POST['r_text'], $_POST['star_rating'] );
			} catch( PDOException $Exception) {
				echo '<span style="color:#880808;text-align:center;"> !! You have already reviewed this book !! </span>';
			}
		}
		?>
		
		<h1>Reviews: </h1>
		
		<?php 
		function select_reviews_for_book($ISBN){
			global $db;
			$query = "
			select * from reviews
			where ISBN = :ISBN;";
			$statement = $db->prepare($query);
			$statement->bindValue(':ISBN', $ISBN);
			$statement->execute();
			$results = $statement->fetchAll();
			$statement->closeCursor();
			return $results;
		}
		
		function select_comments_for_review($review_num){
			global $db;
			$query = "
			select * from comments
			where review_num = :review_num;";
			$statement = $db->prepare($query);
			$statement->bindValue(':review_num', $review_num);
			$statement->execute();
			$results = $statement->fetchAll();
			$statement->closeCursor();
			return $results;
		}
		
		function post_review($ISBN, $username, $r_text, $star_rating){
			global $db;
			$query = "
			insert into reviews
			values(:ISBN, NULL, :username, :r_text, :star_rating, :r_date);";
			$statement = $db->prepare($query);
			$statement->bindValue(':ISBN', $ISBN);
			$statement->bindValue(':username', $username);
			$statement->bindValue(':r_text', $r_text);
			$statement->bindValue(':star_rating', $star_rating);
			$date = date('Y/m/d', time());
			$statement->bindValue(':r_date', $date);
			$statement->execute();
			$statement->closeCursor();
		}
		
		function post_comment($username, $review_num, $c_text){
			global $db;
			$query = "
			insert into comments
			values(:username, NULL, :review_num, :c_text, :c_date);";
			$statement = $db->prepare($query);
			$statement->bindValue(':username', $username);
			$statement->bindValue(':review_num', $review_num);
			$statement->bindValue(':c_text', $c_text);
			$date = date('Y/m/d', time());
			$statement->bindValue(':c_date', $date);
			$statement->execute();
			$statement->closeCursor();
		}
		
		if(isset($_POST['c_text']) and isset($_SESSION['username'])){
			post_comment($_SESSION['username'], $_POST['review_num'], $_POST['c_text']);
			#echo "Posted review.";
		}
		
		if(isset($_POST['ISBN'])){
			$reviews = select_reviews_for_book($_POST['ISBN']);
		} else {
			$reviews = [];
		}
		?>
		<?php foreach ($reviews as $review): ?>
			<div class="solid" style="background-color:gainsboro">
			<h6><?php echo $review['star_rating']." out of 5 stars. Reviewed on ".$review['r_date']."." ?></h6>
			<h3><?php echo $review['username'].": " ?></h3>
			<p> <?php echo $review['r_text'] ?></p>
			</div>
			<?php 
			$comments = select_comments_for_review($review['review_num']);
			foreach ($comments as $comment): 
			?>
			<div class="solid" style = "position:relative; padding-bottom:60px; background-color:silver">
				<h5 class = "sm" style="position:absolute; left:20px; top:0px"><?php echo $comment['username']." replied on ".$comment['c_date'].": " ?></h3>
				<p class ="sm" style="position:absolute; left:20px; top: 20px"> <?php echo "> ".$comment['c_text'] ?></p>
			</div>
			<?php endforeach ?>
			<div class="solid" style = "position:relative; padding-bottom:60px; background-color:silver">
				<form name="comment_form" action="book_page.php" method="post">
				<input type="text" class="form-control" name="c_text" style="position:absolute; left:20px; top:0px" required>
				<input type="submit" class="form-control" value="Reply" style="position:absolute; left:20px; top:28px">
				<input type="hidden" name="review_num" value=<?php echo $review['review_num']?> >
				<input type="hidden" name="ISBN" value="<?php echo $_POST['ISBN']; ?>">
				<input type="hidden" name="title" value="<?php echo $_POST['title']; ?>">
				<input type="hidden" name="authors" value="<?php echo $_POST['authors']; ?>">
				</form>
			</div>
		<?php endforeach ?>
	</body>
	
</html>