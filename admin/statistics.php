<?php
$query = $connection->prepare("SELECT COUNT(*) AS num FROM users WHERE status = 'admin'");
$query->execute();
$admins = $query->fetch(PDO::FETCH_ASSOC);

$query = $connection->prepare("SELECT COUNT(*) AS num FROM users WHERE status = 'user'");
$query->execute();
$users = $query->fetch(PDO::FETCH_ASSOC);

$query = $connection->prepare("SELECT COUNT(*) AS num FROM subscribers");
$query->execute();
$subscribers = $query->fetch(PDO::FETCH_ASSOC);

$query = $connection->prepare("SELECT COUNT(*) AS num FROM posts");
$query->execute();
$posts = $query->fetch(PDO::FETCH_ASSOC);
?>
<?php if (!$isAdmin) { ?>
   <div class="content d-flex align-items-center">
      <div class="col-xl-12">
         <div class="text-center">
            <h4>NOTHING TO SHOW HERE FOR NOW!</h4>
         </div>
      </div>
   </div>
<?php } else { ?>
   <div class="content d-flex align-items-start">
      <div class="col-xl-3 col-md-6 mb-4">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Administrators</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <h4>Total Admins: <?php echo $admins['num']; ?></h4>
                     </div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-users-cog fa-2x text-gray-600"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Users</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <h4>Total Users: <?php echo $users['num']; ?></h4>
                     </div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-users-cog fa-2x text-gray-600"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Subscribers</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <h4>Total Subscribers: <?php echo $subscribers['num']; ?></h4>
                     </div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-users-cog fa-2x text-gray-600"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
         <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
               <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Posts</div>
                     <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <h4>Total Posts: <?php echo $posts['num']; ?></h4>
                     </div>
                  </div>
                  <div class="col-auto">
                     <i class="fas fa-users-cog fa-2x text-gray-600"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php } ?>