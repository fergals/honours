<?php require('/config/dbconnect.php');

$user->logout();

header('Location: index.php');
exit;
?>
