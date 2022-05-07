<?php
$contacts = $connection->query("SELECT * FROM contacts")->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="content">
   <div class="col-lg-12">
      <?php if (empty($contacts)) { ?>
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">NO CONTACTS TO SHOW</h4>
            </div>
         </div>
      <?php } else { ?>
         <div class="content">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header">
                     <h4 class="card-title">Contacts</h4>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive-xxl">
                        <table class="table">
                           <thead class="text-primary">
                              <tr>
                                 <th scope="col">Id</th>
                                 <th scope="col">Name</th>
                                 <th scope="col">Email</th>
                                 <th scope="col">Phone</th>
                                 <th scope="col">Gender</th>
                                 <th scope="col">Favourite Language</th>
                                 <th scope="col">City</th>
                                 <th scope="col">Message</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($contacts as $i => $contact) : ?>
                                 <tr>
                                    <td scope="row"><?php echo $i + 1; ?></td>
                                    <td><?php echo $contact['contact_name']; ?></td>
                                    <td><?php echo $contact['contact_email']; ?></td>
                                    <td><?php echo $contact['contact_phone']; ?></td>
                                    <td><?php echo $contact['contact_gender']; ?></td>
                                    <td><?php echo $contact['contact_favlang']; ?></td>
                                    <td><?php echo $contact['contact_city']; ?></td>
                                    <td><a href="?id=show_contacts&contactid=<?php echo $contact['contact_id']; ?>" class="btn btn-link">CHECKOUT</a></td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php } ?>
   </div>
</div>