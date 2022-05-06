<?php
$search = "%{$_GET['searchquery']}%";

$query = $connection->prepare("SELECT * FROM users WHERE username LIKE :search OR user_email LIKE :search");
$query->bindValue(":search", $search);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if (empty($results)) { ?>
   <div class="content d-flex align-items-center">
      <div class="col-xl-12">
         <div class="text-center">
            <h4>OPSS! NO RECORDS FOR THE SEARCH!</h4>
         </div>
      </div>
   </div>
<?php } else { ?>
   <div class="content d-flex align-items-stretch flex-wrap">
      <?php foreach ($results as $result) : ?>
         <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
               <div class="card-body">
                  <div class="row no-gutters">
                     <div class="col mr-2 text-center flex-column justify-content-between">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><?php if ($result['status'] === 'admin') echo "Administrator";
                                                                                                else echo "User"; ?></div>
                        <div class="h5 mb-3 font-weight-bold text-gray-800">
                           <img src="<?php echo $result['user_profile']; ?>" alt="NO IMAGE TO SHOW">
                        </div>
                        <div class=" h5 mb-0 font-weight-bold text-gray-800">
                           <h4><?php echo $result['username']; ?></h4>
                           <h5><?php echo $result['user_email']; ?></h5>
                           <p><?php echo $result['about_user']; ?></p>

                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="button-container d-flex justify-content-around">
                     <a href="<?php echo $result['facebook_link']; ?>" target="_blank">
                        <button class="btn btn-icon btn-round btn-facebook">
                           <i class="fab fa-facebook"></i>
                        </button>
                     </a>
                     <a href="<?php echo $result['github_link']; ?>" target="_blank">
                        <button class="btn btn-icon btn-round btn-github">
                           <i class="fab fa-github"></i>
                        </button>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php } ?>