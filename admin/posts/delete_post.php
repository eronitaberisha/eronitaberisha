<?php
$id = $_POST['postid'] ?? null;

$query = $connection->prepare("SELECT post_image FROM posts WHERE post_id = :id");
$query->bindValue(":id", $id);
$query->execute();

$post = $query->fetch(PDO::FETCH_ASSOC);

if ($post) {
   unlink($post['post_image']);
}

if (!$id) {
   header("Location: index.php?id=posts");
   exit;
}

$query = $connection->prepare('DELETE FROM posts WHERE post_id = :id');
$query->bindValue(':id', $id);
$query->execute();

header("Location: index.php?id=posts");
