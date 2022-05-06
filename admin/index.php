<?php
require_once 'functions.php';
checkSession("../public/login.php");

// database connection
require_once '../config/dbconnection.php';

include_once "./partials/header.php";
?>
<!-- End Navbar -->

<?php
$path = $_GET['id'] ?? null;

if (isset($_GET['searchquery'])) {
   include "search.php";
} else {
   switch ($path) {
      case 'index':
         include "statistics.php";
         break;
      case 'admins':
         include "users/admins.php";
         break;
      case 'users':
         include "users/users.php";
         break;
      case 'adduser':
      case 'addadmin':
         include "users/add_user.php";
         break;
      case 'edituser':
      case 'editadmin':
         include "users/edit_user.php";
         break;
      case 'deleteuser':
      case 'deleteadmin':
         include "users/delete_user.php";
         break;
      case 'posts':
         include "posts/posts.php";
         break;
      case 'single_post':
         include "posts/single_post.php";
         break;
      case 'addpost':
         include "posts/add_post.php";
         break;
      case 'editpost':
         include "posts/edit_post.php";
         break;
      case 'deletepost':
         include "posts/delete_post.php";
         break;
      case 'subscribers':
         include "contacts/subscribers.php";
         break;
      case 'contacts':
         include "contacts/contacts.php";
         break;
      case 'show_contacts':
         include "contacts/show_contact.php";
         break;
      case 'profile':
         include "users/profile.php";
         break;
      default:
         echo "Something went wrong!";
         break;
   }
}

?>

<!-- FOOTER -->
<?php include_once "./partials/footer.php"; ?>