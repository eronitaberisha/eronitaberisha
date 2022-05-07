<?php
ob_start();

$message = greeting();

$userid = $_SESSION['user_id'];
$query = $connection->prepare('SELECT * FROM users WHERE user_id = :id');
$query->bindValue(":id", $userid);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

$isAdmin = ($user['status'] === 'admin');
$profile_picture = $user["user_profile"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <meta name="keywords" content="HTML, CSS, JavaScript, PHP, MYSQL">
   <meta name="description" content="Projekti per Inxhinieri t'Webit">
   <meta name="author" content="Eronita Berisha">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <!-- FAVICON -->
   <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
   <link rel="icon" type="image/png" href="assets/img/favicon.png">

   <title>Dashboard | Welcome</title>

   <!-- Fonts and icons     -->
   <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
   <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

   <!-- Nucleo Icons -->
   <link href="assets/css/nucleo-icons.css" rel="stylesheet" />

   <!-- CSS Files -->
   <link href="assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />

   <!-- PRELOADER -->
   <?php include_once "../public/inc/preloader.php"; ?>

   <div class="wrapper">
      <div class="sidebar">
         <div class="sidebar-wrapper">
            <div class="logo">
               <a href="?id=index" class="simple-text logo-mini">
                  GA
               </a>
               <a href="?id=index" class="simple-text logo-normal">
                  GROOVE APP
               </a>
            </div>
            <?php if ($isAdmin) { ?>
               <ul class="nav">
                  <li>
                     <a href="?id=index">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>Dashboard</p>
                     </a>
                  </li>
                  <li class="nav-item dropdown ">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="tim-icons icon-satisfied"></i>
                        <p>Users</p>
                     </a>
                     <div class="dropdown-menu dropdown-black" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="?id=admins">Admins</a>
                        <a class="dropdown-item" href="?id=users">Users</a>
                     </div>
                  </li>
                  <li>
                     <a href="?id=posts">
                        <i class="tim-icons icon-pin"></i>
                        <p>Posts</p>
                     </a>
                  </li>
                  <li class="nav-item dropdown ">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="tim-icons icon-satisfied"></i>
                        <p>Contacts</p>
                     </a>
                     <div class="dropdown-menu dropdown-black" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="?id=contacts">Contacts</a>
                        <a class="dropdown-item" href="?id=subscribers">Subscribers</a>
                     </div>
                  </li>
                  <li>
                     <a href="?id=profile">
                        <i class="tim-icons icon-single-02"></i>
                        <p>User Profile</p>
                     </a>
                  </li>
                  <li>
                     <a href="../public/logout.php">
                        <i class="tim-icons icon-button-power"></i>
                        <p>Logout</p>
                     </a>
                  </li>
               </ul>
            <?php } else { ?>
               <ul class="nav">
                  <li>
                     <a href="?id=index">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>Dashboard</p>
                     </a>
                  </li>
                  <li>
                     <a href="?id=posts">
                        <i class="tim-icons icon-pin"></i>
                        <p>Posts</p>
                     </a>
                  </li>
                  <li>
                     <a href="?id=profile">
                        <i class="tim-icons icon-single-02"></i>
                        <p>User Profile</p>
                     </a>
                  </li>
                  <li>
                     <a href="../public/logout.php">
                        <i class="tim-icons icon-button-power"></i>
                        <p>Logout</p>
                     </a>
                  </li>
               </ul>
            <?php } ?>
         </div>
      </div>

      <div class="main-panel">
         <!-- Navbar -->
         <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
            <div class="container-fluid">
               <div class="navbar-wrapper">
                  <div class="navbar-toggle d-inline">
                     <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                     </button>
                  </div>
                  <a class="navbar-brand" href="javascript:void(0)"><?php echo $message . $user['username']; ?></a>
               </div>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
               </button>
               <div class="collapse navbar-collapse" id="navigation">
                  <ul class="navbar-nav ml-auto">
                     <li class="search-bar input-group">
                        <button class=" btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal">
                           <i class="tim-icons icon-zoom-split"></i>
                           <span class="d-lg-none d-md-block"></span>
                        </button>
                     </li>
                     <li class="dropdown nav-item">
                        <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                           <div class="notification d-none d-lg-block d-xl-block"></div>
                           <i class="tim-icons icon-sound-wave"></i>
                           <p class="d-lg-none">
                              Notifications
                           </p>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                           <li class="nav-link"><a href="#" class="nav-item dropdown-item">Mike John responded to your email</a>
                           </li>
                           <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">You have 5 more
                                 tasks</a></li>
                           <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Your friend Michael
                                 is in town</a></li>
                           <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another
                                 notification</a></li>
                           <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another one</a></li>
                        </ul>
                     </li>
                     <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                           <div class="photo">
                              <img src="<?php echo (is_file($profile_picture)) ? $profile_picture : 'assets/img/anime3.png' ?>" alt="Profile Photo">
                           </div>
                           <b class="caret d-none d-lg-block d-xl-block"></b>
                           <p class="d-lg-none">
                              Log out
                           </p>
                        </a>
                        <ul class="dropdown-menu dropdown-navbar">
                           <li class="nav-link"><a href="?id=profile" class="nav-item dropdown-item">Profile</a></li>
                           <li class="dropdown-divider"></li>
                           <li class="nav-link"><a href="../public/logout.php" class="nav-item dropdown-item">Log out</a></li>
                        </ul>
                     </li>
                     <li class="separator d-lg-none"></li>
                  </ul>
               </div>
            </div>
         </nav>
         <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <form class="w-100" method=" GET">
                        <input type="text" name="searchquery" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
                        <input type="submit" style="display: none;">
                     </form>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="tim-icons icon-simple-remove"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>


         <script>
            const elems = document.querySelectorAll(".nav li");
            <?php if (!$isAdmin) { ?>
               if ((window.location.href).includes('index'))
                  elems[0].classList.add("active");
               else if ((window.location.href).includes('posts'))
                  elems[1].classList.add("active");
               else if ((window.location.href).includes('profile'))
                  elems[2].classList.add("active");
            <?php } else { ?>
               if ((window.location.href).includes('index'))
                  elems[0].classList.add("active");
               else if ((window.location.href).includes('admins') || (window.location.href).includes('users'))
                  elems[1].classList.add("active");
               else if ((window.location.href).includes('posts'))
                  elems[2].classList.add("active");
               else if ((window.location.href).includes('subscribers') || (window.location.href).includes('contacts'))
                  elems[3].classList.add("active");
               else if ((window.location.href).includes('profile'))
                  elems[4].classList.add("active");
            <?php } ?>
         </script>