<?php

if (!$isAdmin) {
   header("location: ?id=index");
   exit;
}

$errors = [];
$successMessage = "";
$username = "";
$email = "";
$password = "";

if ($_GET["id"] === "adduser") $status = "USER";
else $status = "ADMIN";

if (isset($_POST['submit_registration'])) {
   $username = $_POST["username"];
   $email = $_POST["email"];
   $password = $_POST["password"];

   $query = $connection->prepare("SELECT * FROM users WHERE username = :username OR user_email = :email");
   $query->bindValue(":username", $username);
   $query->bindValue(":email", $email);
   $query->execute();

   $user = $query->fetch(PDO::FETCH_ASSOC);
   $userName = $user['username'] ?? null;
   $userEmail = $user['user_email'] ?? null;

   if (trim($username) === "") {
      $errors[] = "Username is required!";
   }
   if (trim($email) === "") {
      $errors[] = "Email is required!";
   }
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Invalid email format";
   }
   if (trim($password) === "") {
      $errors[] = "Password is required!";
   }
   if ($username === $userName) {
      $errors[] = "Username already exists!";
   }
   if ($email === $userEmail) {
      $errors[] = "Email already exists!";
   }

   if (empty($errors)) {
      $query = $connection->prepare("INSERT INTO users (username, user_email, user_password, status)
                                                         VALUES (:username, :email, :password, :status)");
      $query->bindValue(":username", $username);
      $query->bindValue(":email", $email);
      $query->bindValue(":password", password_hash($password, PASSWORD_DEFAULT));
      $query->bindValue(":status", strtolower($status));
      $query->execute();

      $successMessage = "Successfully created a new user!";
      $username = "";
      $email = "";
      $password = "";
   }
}
?>

<div class="content">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">NEW <?php if ($_GET["id"] === 'adduser') echo "USER";
                                       else echo "ADMIN"; ?></h4>
         </div>

         <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger" role="alert">
               <?php echo $error; ?>
            </div>
         <?php endforeach; ?>

         <?php if (empty($errors) && isset($_POST["submit_registration"])) : ?>
            <div class="alert alert-success" role="alert">
               <?php echo $successMessage; ?>
            </div>
         <?php endif; ?>

         <div class="card-body">
            <form method="POST">
               <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($_POST['submit_registration']) ? $username : ''; ?>" />
               </div>
               <div class="mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="text" name="email" id="email" class="form-control" value="<?php echo isset($_POST['submit_registration']) ? $email : ''; ?>" />
               </div>
               <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" id="password" class="form-control" value="<?php echo isset($_POST['submit_registration']) ? $password : ''; ?>" />
               </div>
               <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <input type="text" name="status" id="status" class="form-control" value="<?php echo $status; ?>" disabled />
               </div>
               <button type="submit" class="btn btn-primary w-100" name="submit_registration">CREATE ACCOUNT</button>
            </form>
         </div>
      </div>
   </div>
</div>