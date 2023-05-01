<?php
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
   case '/':
      require 'search_page.php';
      break;
   case '/search_page.php':
      require 'search_page.php';
      break;
   case '/profile_page.php':                   // URL (without file name) to a default screen
      require 'profile_page.php';
      break; 
   case '/user_page.php':
      require 'user_page.php';
      break;
   case '/book_page.php':
      require 'book_page.php';
      break;
   case '/logout.php':
      require 'logout.php';
      break;
   case '/sign_in.php':
      require 'sign_in.php';
      break;
   case '/sign_up.php':
      require 'sign_up.php';
      break;
   case '/recommendations.php':
      require 'recommendations.php';
      break;
   case '/add_to_reading_list.php':
      require 'add_to_reading_list.php';
      break;
   case '/book_filter_functions.php':
      require 'book_filter_functions.php';
      break;
   case '/friends_functions.php':
      require 'friends_functions.php';
      break;
   case '/search_users_page.php':
      require 'search_users_page.php';
      break;
   case '/user_filter_functions.php':
      require 'user_filter_functions.php';
      break;
   case '/navbar.php':
      require 'navbar.php';
      break;
   default:
      http_response_code(404);
      exit('Not Found');
}  
?>