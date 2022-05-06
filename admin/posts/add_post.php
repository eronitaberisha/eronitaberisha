<?php
$query = $connection->prepare("SELECT * FROM users WHERE user_id = :id");
$query->bindValue(":id", $userid);
$query->execute();
$currentUser = $query->fetch(PDO::FETCH_ASSOC);

$errors = [];

$title = "";
$description = "";

if (isset($_POST['submit_post'])) {
   $title = $_POST['title'];
   $description = $_POST['description'];
   $category = $_POST['category'] ?? null;

   $image = $_FILES['image'] ?? null;

   if (!is_dir('assets/img/' . $currentUser['username'])) {
      mkdir('assets/img/' . $currentUser['username']);
   }

   $imagePath = '';

   if ($image && file_exists($_FILES['image']['tmp_name'])) {
      $imagePath = 'assets/img/' . $currentUser["username"] . '/posts/' . randomString(8) . strrchr($image['name'], ".");

      if (!is_dir(dirname($imagePath)))
         mkdir(dirname($imagePath));
      move_uploaded_file($image['tmp_name'], $imagePath);
   }
   // } else {
   //    $errors[] = "Please insert a photo!";
   // }

   if (!$title) {
      $errors[] = 'Post title is required!';
   }
   if (!$description) {
      $errors[] = 'Post Description is required!';
   }
   if (!$category) {
      $errors[] = 'Please select a category!';
   }

   if (empty($errors)) {
      $query = $connection->prepare("INSERT INTO posts (post_image, post_title, post_description, post_category,post_author, post_date)
                VALUES (:image, :title, :description, :category, :author, :post_date)");
      if ($image) {
         $query->bindValue(':image', $imagePath);
      }
      $query->bindValue(':title', $title);
      $query->bindValue(':description', $description);
      $query->bindValue(':category', $category);
      $query->bindValue(':author', $userid);
      $query->bindValue(':post_date', date("Y-m-d"));

      $query->execute();
      $successMessage = "Successfully created a new post!";
      $title = "";
      $description = "";
      $category = "";
   }
}

?>
<div class="content">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">NEW POST</h4>
         </div>

         <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger" role="alert">
               <?php echo $error; ?>
            </div>
         <?php endforeach; ?>

         <?php if (empty($errors) && isset($_POST["submit_post"])) : ?>
            <div class="alert alert-success" role="alert">
               <?php echo $successMessage; ?>
            </div>
         <?php endif; ?>

         <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
               <div class="mb-3">
                  <label for="image" class="form-label">Insert Image</label> <br />
                  <input type="file" name="image" id="image" value="<?php echo isset($_POST['submit_post']) ? $image : ''; ?>" />
               </div>
               <div class="mb-3">
                  <label for="title" class="form-label">Post Title</label>
                  <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($_POST['submit_post']) ? $title : ''; ?>" />
               </div>
               <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea rows="4" cols="80" name="description" class="form-control" placeholder="Here can be your description..."><?php echo isset($_POST['submit_post']) ? $description : ''; ?></textarea>
               </div>
               <div class="mb-3">
                  <label for="category" class="form-label">Category</label>
                  <select name="category" id="category" class="form-control">
                     <option value="choose" selected disabled>Choose a Category</option>
                     <option value="sport">Sport</option>
                     <option value="education">Education</option>
                     <option value="adventure">Adventure</option>
                     <option value="gaming">Gaming</option>
                  </select>
               </div>
               <button type="submit" class="btn btn-primary w-100" name="submit_post">CREATE POST</button>
            </form>
         </div>
      </div>
   </div>
</div>