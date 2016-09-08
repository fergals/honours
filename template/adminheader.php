<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Help! - Online Ticketing System</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand">
          <img alt="HELP!" width="25px" src="/images/logo.png">
        </a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="../admin/index.php">Home<span class="sr-only">(current)</span></a></li>
          <li><a href="../admin/newticket.php">Add Ticket</a></li>
          <li><a href="../admin/queues.php">Queues</a></li>
          <li><a href="../admin/categories.php">Categories</a></li>
          <li><a href="../admin/kb.php">KnowledgeBase</a></li>
        </ul>

  <ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
      <a href="../admin/index.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Action</a></li>
        <li><a href="#">Ticket Management</a></li>
        <li><a href="#">Users</a></li>
      </ul>
      <li><a href="../profile.php">Profile</a>
      <li><a href="../logout.php">Logout</a>
    </li>
  </ul>
</div>
    </div>
  </nav>

    <div class="container">
