<?php require_once(dirname(__FILE__)."/../config/dbconnect.php")

$user->logout();

header('Location: index.php');
exit;
?>
