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

$currentTitle = $post['post_title'];
$currentDescription = $post['post_description'];
$currentCateogory = $post['post_category'];

$errors = [];
if (isset($_POST['update_post'])) {
   $title = $_POST['title'];
   $description = $_POST['description'] ?? $currentDescription;
   $category = $_POST['category'] ?? $currentCateogory;

   $image = $_FILES['image'] ?? null;

   if (!is_dir('assets/img/' . $postAuthor['username'])) {
      mkdir('assets/img/' . $postAuthor['username']);
   }

   $imagePath = '';
   if ($image && file_exists($_FILES['image']['tmp_name'])) {
      $imagePath = 'assets/img/' . $postAuthor["username"] . '/posts/' . randomString(8) . strrchr($image['name'], ".");
      if (!is_dir(dirname($imagePath)))
         mkdir(dirname($imagePath));
      move_uploaded_file($image['tmp_name'], $imagePath);
   }

   if (((!$image || $imagePath === "") && ($title === $currentTitle) && ($description === $currentDescription) && ($category === $currentCateogory)))
      $errors[] = "Please make some changes!";
   if (trim($title) === "")
      $errors[] = "Title is required!";
   if (trim($description) === "")
      $errors[] = "Description is required!";
   if (!$category)
      $errors[] = "Please choose a category!";


   if (empty($errors)) {
      $query = $connection->prepare("UPDATE posts SET post_image = :image, 
                                        post_title = :title, 
                                        post_description = :description, 
                                        post_category = :category,
                                        post_date = :post_date 
                                        WHERE post_id = :id");
      $query->bindValue(":id", $postid);
      if (!empty($imagePath)) {
         unlink($post['post_image']);
         $query->bindValue(':image', $imagePath);
      } else {
         $query->bindValue(':image', $post['post_image']);
      }
      $query->bindValue(':title', $title);
      $query->bindValue(':description', $description);
      $query->bindValue(':category', $category);
      $query->bindValue(':post_date', date("Y-m-d"));

      $query->execute();
      header("Location: index.php?id=posts");
   }
}
?>

<div class="content">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">UPDATE POST</h4>
         </div>

         <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger" role="alert">
               <?php echo $error; ?>
            </div>
         <?php endforeach; ?>

         <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
               <div class="mb-3">
                  <img width="250" class="mb-4" src="<?php echo $post['post_image']; ?>" alt="no image to show"> <br />
                  <label for="image" class="form-label">Insert Image</label> <br />
                  <input type="file" name="image" id="image" />
               </div>
               <div class="mb-3">
                  <label for="title" class="form-label">Post Title</label>
                  <input type="text" name="title" id="title" class="form-control" value="<?php echo $currentTitle; ?>" />
               </div>
               <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea rows="4" cols="80" name="description" class="form-control" placeholder="Here can be your description..."><?php echo $currentDescription; ?></textarea>
               </div>
               <div class="mb-3">
                  <label for="category" class="form-label">Category</label>
                  <select name="category" id="category" class="form-control">
                     <option value="<?php echo $currentCateogory; ?>" selected disabled><?php echo $currentCateogory; ?></option>
                     <option value="sport">Sport</option>
                     <option value="education">Education</option>
                     <option value="adventure">Adventure</option>
                     <option value="gaming">Gaming</option>
                  </select>
               </div>
               <button type="submit" class="btn btn-primary w-100" name="update_post">CREATE POST</button>
            </form>
         </div>
      </div>
   </div>
</div>