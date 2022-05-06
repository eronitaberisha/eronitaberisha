<?php
$postid = $_GET['postid'] ?? null;

if (!$postid) {
   header("Location: index.php?id=posts");
   exit;
}

$query = $connection->prepare('SELECT * FROM posts WHERE post_id = :id');
$query->bindValue(':id', $postid);
$query->execute();
$post = $query->fetch(PDO::FETCH_ASSOC);

$query = $connection->prepare('SELECT username FROM posts p inner join users u on p.post_author = u.user_id WHERE u.user_id = :author');
$query->bindValue(":author", $post["post_author"]);
$query->execute();
$postAuthor = $query->fetch(PDO::FETCH_ASSOC);

?>

<div class="content">
   <div class="container">
      <div class="card w-75 mx-auto">
         <img width="100%" height="350" style="object-fit: fill;" class="card-img-top" src="<?php echo $post['post_image']; ?>" alt="Card image cap">
         <div class="card-body">
            <h1 class="card-title text-center"><?php echo $post['post_title']; ?></h1>
            <p class="card-text my-4">
               <?php echo $post['post_description']; ?>
            </p>
            <p class="card-text">
               <small class="text-muted ">
                  <i class="far fa-user ml-2"></i><?php echo $postAuthor['username']; ?>
                  <i class="fas fa-calendar-alt ml-2"></i><?php echo $post['post_date']; ?>
               </small>
            </p>
         </div>
      </div>
   </div>
</div>