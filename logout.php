<?php require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

$user->logout();

header('Location: index.php');
exit;
?>
