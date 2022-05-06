<?php
if (!$isAdmin) {
  header("location: ?id=index");
  exit;
}
$users = $connection->query("SELECT * FROM users WHERE status = 'user' ORDER BY user_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
  <div class="col-lg-12">
    <?php if (empty($users)) { ?>
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">NO USERS TO SHOW</h4>
          <a href="?id=adduser" class="btn btn-primary my-2">ADD USER</a>
        </div>
      </div>
    <?php } else { ?>
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Users Table</h4>
          <a href="?id=adduser" class="btn btn-primary">ADD USER</a>
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
                <?php foreach ($users as $i => $user) : ?>
                  <tr>
                    <td scope="row"><?php echo $i + 1; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['user_email']; ?></td>
                    <td title="<?php echo $user['user_password']; ?>"><?php echo substr($user['user_password'], 0, 50) . '...'; ?></td>
                    <td class="text-center"><?php echo $user['status']; ?></td>
                    <td>
                      <a href="?id=edituser&userid=<?php echo $user["user_id"]; ?>" rel="tooltip" class="btn btn-info btn-sm btn-round btn-icon d-block mx-auto">
                        <i class="tim-icons icon-single-02"></i>
                      </a>
                    </td>
                    <td>
                      <form action="?id=deleteuser" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $user["user_id"]; ?>">
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