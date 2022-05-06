<?php

if (!$isAdmin) {
   header("location: ?id=index");
   exit;
}

$userid = $_SESSION["user_id"];

$query = $connection->prepare("SELECT * FROM users WHERE user_id = :id");
$query->bindValue(":id", $userid);
$query->execute();
$currentAdmin = $query->fetch(PDO::FETCH_ASSOC);


$query = $connection->prepare("SELECT * FROM users WHERE status = 'admin' AND username != :username ORDER BY user_id");
$query->bindValue(":username", $currentAdmin['username']);
$query->execute();
$admins = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content">
   <div class="col-lg-12">
      <?php if (empty($admins)) { ?>
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">NO ADMINS TO SHOW</h4>
               <a href="?id=addadmin" class="btn btn-primary my-2">ADD ADMIN</a>
            </div>
         </div>
      <?php } else { ?>
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Admins Table</h4>
               <a href="?id=addadmin" class="btn btn-primary">ADD ADMIN</a>
            </div>
            <div class="card-body">
               <div class="table-responsive-xxl">
                  <table class="table">
                     <thead class="text-primary">
                        <tr>
                           <th scope="col">Id</th>
                           <th scope="col">Username</th>
                           <th scope="col">Email</th>
                           <th scope="col">Password</th>
                           <th scope="col" class="text-center">Status</th>
                           <th scope="col" colspan="2" class="text-center">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($admins as $i => $admin) : ?>
                           <tr>
                              <td scope="row"><?php echo $i + 1; ?></td>
                              <td><?php echo $admin['username']; ?></td>
                              <td><?php echo $admin['user_email']; ?></td>
                              <td title="<?php echo $admin['user_password']; ?>"><?php echo substr($admin['user_password'], 0, 50) . '...'; ?></td>
                              <td class="text-center"><?php echo $admin['status']; ?></td>
                              <td>
                                 <a href="?id=editadmin&userid=<?php echo $admin["user_id"]; ?>" rel="tooltip" class="btn btn-info btn-sm btn-round btn-icon d-block mx-auto">
                                    <i class="tim-icons icon-single-02"></i>
                                 </a>
                              </td>
                              <td>
                                 <form action="?id=deleteadmin" method="POST">
                                    <input type="hidden" name="userid" value="<?php echo $admin["user_id"]; ?>">
                                    <button type="submit" rel="tooltip" class="btn btn-danger btn-sm btn-round btn-icon d-block mx-auto">
                                       <i class="tim-icons icon-simple-remove "></i>
                                    </button>
                                 </form>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      <?php } ?>
   </div>
</div>