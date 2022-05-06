<?php
if (!$isAdmin) {
  header("location: ?id=index");
  exit;
}

$id = $_GET['userid'] ?? null;

if (!$id) {
  header("Location: index.php?id=users");
  exit;
}

$query = $connection->prepare('SELECT * FROM users WHERE user_id = :id');
$query->bindValue(':id', $id);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  header("Location: index.php?id=users");
  exit;
}

$currentUsername = $user['username'];
$currentEmail = $user['user_email'];
$currentPassword = $user['user_password'];
$currentStatus = $user['status'];

$errors = [];
if (isset($_POST['submit_update'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $status = $_POST['status'];

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
  $user_status = $user['status'] ?? null;

  if ((($username === $user_name) && ($email === $user_email) && ($password === $user_password) && ($status === $user_status)) || (($username === $currentUsername) && ($email === $currentEmail) && ($password === $currentPassword) && ($status === $currentStatus)))
    $errors[] = "Please make some changes!";
  if (!$username || trim($username) === "")
    $errors[] = "Username is required!";
  if (!$email || trim($username) === "")
    $errors[] = "Email is required!";
  if (!$password || trim($username) === "")
    $errors[] = "Password is required!";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Invalid email format";
  if ($username === $user_name)
    $errors[] = "Username already exists!";
  if ($email === $user_email)
    $errors[] = "Email already exists!";

  if (empty($errors)) {
    $query = $connection->prepare("UPDATE users SET username = :username, 
                                        user_email = :email, 
                                        user_password = :password, 
                                        status = :status WHERE user_id = :id");
    $query->bindValue(':id', $id);
    $query->bindValue(':username', $username);
    $query->bindValue(':email', $email);
    if ($currentPassword === $password) $query->bindValue(':password', $password);
    else $query->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
    $query->bindValue(':status', $status);

    $query->execute();

    if ($_GET["id"] === 'edituser')
      header("Location: index.php?id=admins");
    else
      header("Location: index.php?id=users");
  }
}
?>

<div class="content">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">UPDATE <?php if ($_GET["id"] === 'edituser') echo "USER";
                                      else echo "ADMIN"; ?></h4>
      </div>

      <?php foreach ($errors as $error) : ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error; ?>
        </div>
      <?php endforeach; ?>

      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $currentUsername; ?>" />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="text" name="email" id="email" class="form-control" value="<?php echo $currentEmail; ?>" />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="<?php echo $currentPassword; ?>" />
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
              <option value="<?php echo $currentStatus; ?>" selected><?php echo $currentStatus; ?></option>
              <?php if ($_GET['id'] === 'edituser') { ?>
                <option value="admin">admin</option>
              <?php } else { ?>
                <option value="user">user</option>
              <?php } ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100" name="submit_update">UPDATE ACCOUNT</button>
        </form>
      </div>
    </div>
  </div>
</div>