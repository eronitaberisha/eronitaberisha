<?php
$userid = $_SESSION["user_id"];

$query = $connection->prepare("SELECT * FROM users WHERE user_id = :id");
$query->bindValue(":id", $userid);
$query->execute();
$currentUser = $query->fetch(PDO::FETCH_ASSOC);

$currentUsername = $currentUser["username"];
$currentEmail = $currentUser["user_email"];
$currentPassword = $currentUser["user_password"];
$currentProfilePic = $currentUser["user_profile"];
$currentAbout = $currentUser["about_user"];
$currentFacebook = $currentUser["facebook_link"];
$currentGithub = $currentUser["github_link"];

$errors = [];
if (isset($_POST['submit_profile'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $facebook = $_POST['facebook'];
  $github = $_POST['github'];
  $about = $_POST['description'];

  if ($currentUsername !== $username) {
    $query = $connection->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindValue(":username", $username);
    $query->execute();
  } else if ($currentEmail !== $email) {
    $query = $connection->prepare("SELECT * FROM users WHERE user_email = :email");
    $query->bindValue(":email", $email);
    $query->execute();
  }

  $user = $query->fetch(PDO::FETCH_ASSOC);
  $user_name = $user['username'] ?? null;
  $user_email = $user['user_email'] ?? null;
  $user_password = $user['user_password'] ?? null;

  // Remove all illegal characters from a url
  $facebook = filter_var($facebook, FILTER_SANITIZE_URL);
  $github = filter_var($github, FILTER_SANITIZE_URL);

  if ((($username === $user_name) && ($email === $user_email) && ($password === $user_password)) || (($username === $currentUsername) && ($email === $currentEmail) && ($password === $currentPassword) && ($facebook === $currentFacebook) && ($github === $currentGithub) && (trim($about === ""))))
    $errors[] = "Please make some changes!";
  if (trim($username) === "")
    $errors[] = "Username is required!";
  if (trim($email) === "")
    $errors[] = "Email is required!";
  if (trim($password) === "")
    $errors[] = "Password is required!";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Invalid email format";
  if ($facebook && !filter_var($facebook, FILTER_VALIDATE_URL))
    $errors[] = "Invalid facebook url format";
  if ($github && !filter_var($github, FILTER_VALIDATE_URL))
    $errors[] = "Invalid github url format";
  if ($username === $user_name)
    $errors[] = "Username already exists!";
  if ($email === $user_email)
    $errors[] = "Email already exists!";

  if (empty($errors)) {
    $query = $connection->prepare("UPDATE users SET username = :username, 
                                        user_email = :email, 
                                        user_password = :password, 
                                        about_user = :about,
                                        facebook_link = :facebook,
                                        github_link = :github
                                        WHERE user_id = :id");
    $query->bindValue(':id', $userid);
    $query->bindValue(':username', $username);
    $query->bindValue(':email', $email);
    if ($currentPassword === $password) $query->bindValue(':password', $password);
    else $query->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
    if (trim($about) !== "")
      $query->bindValue(':about', $about);
    else
      $query->bindValue(':about', $currentAbout);
    $query->bindValue(':facebook', $facebook);
    $query->bindValue(':github', $github);

    $query->execute();

    $successMessage = "Successfully Changed your Account! Wait 3 Seconds!";
    header("refresh:3;url=?id=profile");
  }
}

if (isset($_POST['submit_photo'])) {
  $image = $_FILES['profile_pic'] ?? null;

  $imagePath = '';

  if (!is_dir('assets/img')) {
    mkdir('assets/img');
  }

  // echo !file_exists($_FILES['profile_pic']['tmp_name']) || !is_uploaded_file($_FILES['profile_pic']['tmp_name']);

  if ($image && file_exists($_FILES['profile_pic']['tmp_name'])) {
    if ($currentUser['user_profile']) {
      unlink($currentUser['user_profile']);
    }
    $imagePath = 'assets/img/' . $currentUser["username"] . '/' . $image['name'];

    if (!is_dir(dirname($imagePath)))
      mkdir(dirname($imagePath));
    move_uploaded_file($image['tmp_name'], $imagePath);

    $query = $connection->prepare("UPDATE users SET user_profile = :url WHERE user_id = :id");
    $query->bindValue(":url", $imagePath);
    $query->bindValue(":id", $userid);

    if ($query->execute()) {
      $successMessage = "Your avatar has been changed! Wait 3 seconds!";
      header('refresh: 3; URL=?id=profile');
    }
  } else {
    $errors[] = "Please insert a photo!";
  }
}
?>

<div class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="title">Edit Profile</h5>
        </div>

        <?php foreach ($errors as $error) : ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
          </div>
        <?php endforeach; ?>

        <?php if ((empty($errors) && isset($_POST["submit_profile"])) || (empty($errors) && isset($_POST["submit_photo"]))) : ?>
          <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
          </div>
        <?php endif; ?>

        <form action="?id=profile" method="POST">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 col-md-6 pr-md-1">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" value="<?php echo $currentUsername; ?>">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 pl-md-1">
                <div class="form-group">
                  <label>Email address</label>
                  <input type="email" name="email" class="form-control" value="<?php echo $currentEmail; ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" value="<?php echo $currentPassword; ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-md-4 pr-md-1">
                <div class="form-group">
                  <label>Facebook</label>
                  <input type="text" name="facebook" class="form-control" placeholder="https://www.facebook.com/#username" value="<?php echo $currentFacebook; ?>">
                </div>
              </div>
              <div class="col-lg-6 col-md-4 pl-md-1">
                <div class="form-group">
                  <label>Github</label>
                  <input type="text" name="github" class="form-control" placeholder="https://www.github.com/#username" value="<?php echo $currentGithub; ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>About Me</label>
                  <textarea rows="4" cols="80" name="description" class="form-control" placeholder="Here can be your description..."><?php $currentAbout; ?></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" name="submit_profile" class="btn btn-fill btn-primary">Save</button>
            <button type="submit" name="submit_photo" form="profile_form" class="btn btn-fill btn-primary">CHANGE PHOTO</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-user">
        <div class="card-body">
          <p class="card-text">
          <div class="author">
            <div class="block block-one"></div>
            <div class="block block-two"></div>
            <div class="block block-three"></div>
            <div class="block block-four"></div>
            <form id="profile_form" action="?id=profile" method="POST" enctype="multipart/form-data">
              <label for="profile_pic" style="cursor: pointer;">
                <img class="avatar" src="<?php echo (is_file($profile_picture)) ? $profile_picture : 'assets/img/anime3.png' ?>" alt="...">
              </label>
              <input type="file" name="profile_pic" id="profile_pic" style="display: none;">
            </form>
            <h5 class="title"><?php echo $currentUsername; ?></h5>
            <p class="description">
              <?php echo $currentUser['status']; ?>
            </p>
          </div>
          </p>
          <div class="card-description">
            <?php echo $currentAbout; ?>
          </div>
        </div>
        <div class="card-footer">
          <div class="button-container">
            <a href="<?php echo $currentFacebook; ?>" target="_blank">
              <button class="btn btn-icon btn-round btn-facebook">
                <i class="fab fa-facebook"></i>
              </button>
            </a>
            <a href="<?php echo $currentGithub; ?>" target="_blank">
              <button class="btn btn-icon btn-round btn-github">
                <i class="fab fa-github"></i>
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>