<?php
$subscribers = $connection->query("SELECT * FROM subscribers ORDER BY subscriber_email")->fetchAll(PDO::FETCH_ASSOC);

// UNSUBSCRIBE
if (isset($_GET["delete_subscriber"])) {
  $sub_id = $_GET['delete_subscriber'];

  $query = $connection->prepare('DELETE FROM subscribers WHERE id = :id');
  $query->bindValue(':id', $sub_id);
  $query->execute();

  header("location: ?id=subscribers");
}
?>

<div class="content">
  <div class="col-lg-12">
    <?php if (empty($subscribers)) { ?>
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">NO SUBSCRIBERS TO SHOW</h4>
        </div>
      </div>
    <?php } else { ?>
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Subscribers</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive-xxl">
            <table class="table">
              <thead class="text-primary">
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">Subscriber Email</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($subscribers as $i => $subscriber) : ?>
                  <tr>
                    <td scope="row"><?php echo $i + 1 ?></td>
                    <td><?php echo $subscriber['subscriber_email']; ?></td>
                    <td>
                      <a href="?id=subscribers&delete_subscriber=<?php echo $subscriber["id"]; ?>" class="btn btn-danger d-block w-50 mx-auto">UNSUBSCRIBE</a>
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