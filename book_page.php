<?php require 'connect-db.php'; ?>

<!DOCTYPE html>
<html>

	<?php 
    // Note: Hard-setting the ISBN testing purposes.
		$_POST['ISBN'] = "0007195303";
		$_POST['title'] = "The Known World";
		$_POST['authors'] = "Edward P. Jones";
	?>


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
		
		if(isset($_POST['ISBN'])){
			$reviews = select_reviews_for_book($_POST['ISBN']);
		} else {
			$reviews = [];
		}
		?>
		<?php foreach ($reviews as $review): ?>
			<div class="solid">
			<h6><?php echo $review['star_rating']." out of 5 stars. Reviewed on ".$review['r_date']."." ?></h6>
			<h3><?php echo $review['username'].": " ?></h3>
			<p> <?php echo $review['r_text'] ?></p>
			</div>
			<?php 
			$comments = select_comments_for_review($review['review_num']);
			foreach ($comments as $comment): 
			?>
			<div class = "dotted">
			<h3 class = "sm"><?php echo $comment['username']." replied on ".$comment['c_date'].": " ?></h3>
			<p class ="sm"> <?php echo $comment['c_text'] ?></p>
			</div>
			<?php endforeach ?>
		<?php endforeach ?>
	</body>
	
</html>