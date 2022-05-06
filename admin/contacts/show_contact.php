<?php
$contactid = $_GET["contactid"] ?? null;

if (!$contactid) {
   header("location: ?id=contacts");
   exit;
}

$query = $connection->prepare("SELECT * FROM contacts WHERE contact_id = :id");
$query->bindValue(":id", $contactid);
$query->execute();
$contact = $query->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
   header("location: ?id=contacts");
   exit;
}
?>

<div class="content">
   <div class="col-lg-12">
      <div class="card bg-warning mb-3">
         <div class="card-header text-dark d-flex justify-content-between align-items-center">
            <h1 class="card-title text-dark"><?php echo $contact["contact_name"]; ?></h1>
            <span><?php echo $contact["contact_id"]; ?></span>
         </div>
         <div class="card-body">
            <h3 class="card-title text-dark">Email:
               <span>
                  <?php echo $contact["contact_email"]; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">Phone Number:
               <span>
                  <?php echo $contact['contact_phone']; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">Gender:
               <span>
                  <?php echo $contact['contact_gender']; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">Favourite Language:
               <span>
                  <?php echo $contact['contact_favlang']; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">City:
               <span>
                  <?php echo $contact['contact_city']; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">Subject:
               <span>
                  <?php echo $contact["contact_subject"]; ?>
               </span>
            </h3>
            <h3 class="card-title text-dark">Message:
               <span>
                  <?php echo $contact["contact_message"]; ?>
               </span>
            </h3>

            <a href="?id=contacts&delete_contact=<?php echo $contact['contact_id']; ?>" class="btn btn-danger float-right">DELETE</a>
            <a href="?id=contacts" class="btn btn-info float-right">BACK</a>
         </div>
      </div>
   </div>
</div>