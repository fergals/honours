<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

//check if user is logged in, if so redirect to index.php
  if ($user->is_logged_in() ) {
    header('Location: main.php');
  }

  //process login form if submitted
  if(isset($_POST['login'])){

  	$username = $_POST['loginusername'];
  	$password = $_POST['loginpassword'];

  	if($user->login($username,$password)){
  		$_SESSION['username'] = $username;
  		header('Location: main.php');
  		exit;

  	} else {
  		$error[] = 'Wrong username or password or your account has not been activated.';
  	}

  }

//Register Button
if(isset($_POST['register'])) {
    if(strlen($_POST['username']) < 3) {
      $error[] = "Username is too short"; }

      else {
        $stmt = $db->prepare('SELECT username FROM users WHERE username = :username');
        $stmt->execute(array(':username' => $_POST['username']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty($row['username'])){
          $error[] = 'Username is already in use, please choose another one';
        }
      }

    if(strlen($_POST['password1']) < 8) {
      $error[] = 'Password is too short';
    }

    if($_POST['password1'] != $_POST['password2']) {
      $error[] = 'Passwords do not match';
    }


    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error[] = 'Please enter a valid email address';
    }
    else {
      $stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
      $stmt->execute(array(':email' => $_POST['email']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if(!empty($row['email'])){
        $error[]= 'Email address is already in use, choose another';
      }
    }

    if(strlen($_POST['phonenumber']) < 11) {
      $error[] = 'Telephone number is too short';
    }


//No errors then submit to DB
if(!isset($error)){
  //hash password
  $hashpassword = $user->password_hash($_POST['password1'], PASSWORD_BCRYPT);

  //create activatasion code
	$activasion = md5(uniqid(rand(),true));
  $department = "NONE";
  $usertype = "Registered";
  $creationdate = date("Y-m-d H:i:s");

  try {

    $stmt = $db->prepare('INSERT INTO users (username, firstname, surname, dateofbirth, password, email, phonenumber, department, usertype, creationdate, active) VALUES (:username, :firstname, :surname, :dateofbirth, :password, :email, :phonenumber, :department, :usertype, :creationdate, :active)');
    $stmt->execute(array(
                          ':username' => $_POST['username'],
                          ':firstname' => $_POST['firstname'],
                          ':surname' => $_POST['surname'],
                          ':password' => $hashpassword,
                          ':dateofbirth' => $_POST['dateofbirth'],
                          ':email' => $_POST['email'],
                          ':phonenumber' => $_POST['phonenumber'],
                          ':department' => $department,
                          ':usertype' => $usertype,
                          ':creationdate' => $creationdate,
                          ':active' => $activasion ));
			$id = $db->lastInsertId('id');
			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at HELP! Online Support System</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: index.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}

// Reset Password
if(isset($_POST['forgotsubmit'])){

	//email validation
	if(!filter_var($_POST['forgotemail'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['forgotemail']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$error[] = 'Email provided is not on recognised.';
		}

	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activasion code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE users SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Password Reset";
			$body = "<p>Someone requested that the password be reset on " . $pagetitle . "</p>
			<p>If this was a mistake, just ignore this email and nothing will happen.</p>
			<p>To reset your password, visit the following address: <a href='".DIR."resetPassword.php?key=$token'>".DIR."resetPassword.php?key=$token</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: index.php?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
?>

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
    // Check if token passed is same as users in DB
    $stmt = $db->prepare('SELECT resetToken, resetComplete FROM users WHERE resetToken = :token');
    $stmt->execute(array(':token' => $_GET['key']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //if no token from db then kill the page
    if(empty($row['resetToken'])){
      $stop = 'Invalid token, please use the link provided in the reset email.';
    }
    elseif($row['resetComplete'] == 'Yes') {
    $stop = 'Your password has already been changed!';
  }
  //if form has been submitted process it
  if(isset($_POST['submit'])){

  	//basic validation
  	if(strlen($_POST['password']) < 3){
  		$error[] = 'Password is too short.';
  	}

  	if(strlen($_POST['passwordConfirm']) < 3){
  		$error[] = 'Confirm password is too short.';
  	}

  	if($_POST['password'] != $_POST['passwordConfirm']){
  		$error[] = 'Passwords do not match.';
  	}

  	//if no errors have been created carry on
  	if(!isset($error)){

  		//hash the password
  		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

  		try {

  			$stmt = $db->prepare("UPDATE users SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
  			$stmt->execute(array(
  				':hashedpassword' => $hashedpassword,
  				':token' => $row['resetToken']
  			));

  			//redirect to index page
  			header('Location: index.php?action=resetAccount');
  			exit;

  		//else catch the exception and show the error.
  		} catch(PDOException $e) {
  		    $error[] = $e->getMessage();
  		}

  	}

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
      <li><a href="" data-toggle="modal" data-target="#loginmodal">Login</a>
    </li>
  </ul>
</div>
    </div>
  </nav>

    <div class="container">
      <?php if(isset($stop)){

	    		echo "<p class='bg-danger'>$stop</p>";

	    	} else { ?>
          <h2>Change Password</h2>
          <form role="form" method="post" action="" autocomplete="off">
            <div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="1">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="1">
							</div>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Change Password" class="btn btn-primary btn-block btn-lg" tabindex="3"></div>
					</div>
				</form>
        <?php } ?>

  </body>
  </html>
