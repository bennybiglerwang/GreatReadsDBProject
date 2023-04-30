<!DOCTYPE html>
<html>

<style>

.topnav {
  background-color: #FECD5A;
  overflow: hidden;
}

.topnav a {
  float: right;
  color: #808080;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #FFA500;
  color: white;
}
</style>

<header class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-collapse collapse">
            <ul class="topnav">
            <?php 
                    // If user is logged in, show profile and logout links
                    if(isset($_SESSION['username'])) { ?>
                        <a href="logout.php">Logout</a>
                        <a href="search_page.php">Home</a> 
                        <a href="profile_page.php">Profile</a>
                        <a href="recommendations.php">My Recs</a>
                        <a href="search_users_page.php">Users</a>
                <?php 
                    } else { // If user is not logged in, show log in link
                ?>
                        <a class="active" href="sign_in.php">Log In</a>
                <?php 
                    } 
                ?>
            </ul>
        </div>
    </div>
</header>


</html>
