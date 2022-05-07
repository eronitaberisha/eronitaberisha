<?php
$query = $connection->prepare("SELECT username FROM users WHERE user_id = :userid");
$query->bindValue(":userid", $userid);
$query->execute();
$currentUser = $query->fetch(PDO::FETCH_ASSOC);

if ($currentUser['username'] === 'eronitaberisha') {
   $query = $connection->prepare("SELECT posts.*, users.username 
                              FROM posts LEFT JOIN users ON 
                              posts.post_author = users.user_id 
                              ORDER BY posts.post_date ");
   $query->bindValue(":userid", $userid);
   $query->execute();
} else {
   $query = $connection->prepare("SELECT posts.*, users.username 
                              FROM posts LEFT JOIN users ON 
                              posts.post_author = users.user_id 
                              WHERE posts.post_author = 
                              (SELECT users.user_id FROM users WHERE users.user_id = :userid) 
                              ORDER BY posts.post_date ");
   $query->bindValue(":userid", $userid);
   $query->execute();
}
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
   <div class="col-lg-12">
      <?php if (empty($posts)) { ?>
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">NO POSTS TO SHOW</h4>
               <a href="?id=addpost" class="btn btn-primary my-2">ADD POST</a>
            </div>
         </div>
      <?php } else { ?>
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Posts Table</h4>
               <a href="?id=addpost" class="btn btn-primary">ADD POST</a>
            </div>
            <div class="card-body">
               <div class="table-responsive-xxl">
                  <table class="table">
                     <thead class="text-primary">
                        <tr>
                           <th scope="col">Id</th>
                           <th scope="col">Image</th>
                           <th scope="col">Title</th>
                           <th scope="col">Description</th>
                           <th scope="col">Category</th>
                           <th scope="col">Author</th>
                           <th scope="col">Date</th>
                           <th scope="col" colspan="2" class="text-center">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($posts as $i => $post) : ?>
                           <tr>
                              <td scope="col"><?php echo $i + 1; ?></td>
                              <td>
                                 <a href="?id=single_post&postid=<?php echo $post['post_id']; ?>">
                                    <img src="<?php echo $post['post_image']; ?>" alt="no photo" width="150" height="100">
                                 </a>
                              </td>
                              <td title="<?php echo $post['post_title']; ?>"><?php echo $post['post_title']; ?></td>
                              <td title="<?php echo $post['post_description']; ?>"><?php echo substr($post['post_description'], 0, 30) . '...'; ?></td>
                              <td><?php echo $post["post_category"]; ?></td>
                              <td><?php echo $post['username']; ?></td>
                              <td><?php echo $post['post_date']; ?></td>
                              <td>
                                 <a href="?id=editpost&postid=<?php echo $post["post_id"]; ?>" rel="tooltip" class="btn btn-info btn-sm btn-round btn-icon d-block mx-auto">
                                    <i class="tim-icons icon-single-02"></i>
                                 </a>
                              </td>
                              <td>
                                 <form action="?id=deletepost" method="POST">
                                    <input type="hidden" name="postid" value="<?php echo $post["post_id"]; ?>">
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