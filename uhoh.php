<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<html lang="en">
  <head>
  <title><?php if(isset($pagetitle)){ echo $pagetitle; }?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="css/bootstrap/js/bootstrap.js"></script>
    <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
  <?php
    // check for errors
    if(isset($error)){
      foreach($error as $error){
        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
      }
    }

    //if successfull
    if(isset($_GET['action']) && $_GET['action'] == 'joined'){
      echo "<div class='alert alert-success' role='alert'>Successfully registered</div>";
    }

   ?>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand">
          <img alt="HELP!" width="25px" src="/images/logo.png">
        </a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="../index.php">Home<span class="sr-only">(current)</span></a></li>
          <li><a href="../newticket.php">Submit New Ticket</a></li>
          <li><a href="../knowledge.php">Knowledge Base</a></li>
        </ul>

  <ul class="nav navbar-nav navbar-right">
      <li><a href="../login.php">Login</a>
    </li>
  </ul>
</div>
    </div>
  </nav>

    <div class="container">
      You are not authorised to view this page
    </div>

  </body>

  </html>
