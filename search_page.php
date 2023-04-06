<?php require 'connect-db.php';
function selectAllBooks(){

    global $db;
    
    $query="select * from books";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
    
function filterByTitle(){

    global $db;

    $query = "SELECT * FROM books
            WHERE (`title` LIKE '%".$_GET['search_query']."%')";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
}
 
if (isset($_POST['option_selected']))
{
    $filter = $_POST['option_selected'];
    echo "$filter";
}

if ($filter == 'title'){
    $search_results = filterByTitle();
}


//var_dump($search_results);

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
                        <form id="search-form" method="GET">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row no-gutters">
                                        <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                                          <form method="post" action="search_page.php">  
                                            <select name="option_selected">
                                              <option name='title'>Title</option>
                                              <option name='author'>Author</option>
                                              <option name='isbn'>ISBN</option>
                                              <option name='language'>Language</option>
                                            </select>
                                          </form> 
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
            <th>Author</th>         
            <th>Average Rating</th> 
            <th>Details?</th>        
            <th>Rate?</th>  
         </tr>
         </thead>
         <?php foreach ($search_results as $item): ?>
            <tr>
               <td><?php echo $item['title']; ?></td>
               <td><?php echo $item['author']; ?></td>        
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