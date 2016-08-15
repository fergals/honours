<?php require_once 'config/dbconnect.php';

$user->logout();

header('Location: index.php');
exit;
?>
