<?php
if (!$isAdmin) {
   header("location: ?id=index");
   exit;
}

$id = $_POST['userid'] ?? null;

if (!$id) {
   header("Location: index.php?id=users");
   exit;
}

$query = $connection->prepare('DELETE FROM users WHERE user_id = :id');
$query->bindValue(':id', $id);
$query->execute();

if ($_GET["id"] === 'deleteuser')
   header("Location: index.php?id=users");
else
   header("Location: index.php?id=admins");
