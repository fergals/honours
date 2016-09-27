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
			<p>If this was a mistake, just ignore this email.</p>
			<p>To reset your password, visit the following address: <a href='".DIR."reset.php?key=$token'>".DIR."reset.php?key=$token</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEMAIL);
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
      <li><a href="" data-toggle="modal" data-target="#loginmodal">Login</a>
    </li>
  </ul>
</div>
    </div>
  </nav>

    <div class="container">
      <div class="indexcontainer">
      <div class="row">
        <div class="col-md-4"><div class="indexbox"><p>
          <span class="glyphicon glyphicon-envelope" style="font-size:15em"></span></p>
        </div>
      </div>

        <div class="col-md-4"><div class="indexbox"><p><span class="glyphicon glyphicon-question-sign" style="font-size:15em"></span></p>
        </div>
      </div>

        <div class="col-md-4"><div class="indexbox"><p><span class="glyphicon glyphicon-user" style="font-size:15em"></span>
        </p>
      </div>
    </div>
      </div>
    </div>
  </div>


  <div class="modal fade bs-modal-sm" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade active in" id="signin">
              <form action="" method="post" class="form-horizontal">
              <fieldset>
              <!-- Sign In Form -->
              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="userid">Username:</label>
                <div class="controls">
                  <input required="" id="userid" name="loginusername" type="text" class="form-control" placeholder="Username" class="input-medium" required="">
                </div>
              </div>

              <!-- Password input-->
              <div class="control-group">
                <label class="control-label" for="passwordinput">Password:</label>
                <div class="controls">
                  <input required="" id="passwordinput" name="loginpassword" class="form-control" type="password" placeholder="********" class="input-medium">
                </div>
              </div>


              <!-- Button -->
              <div class="control-group">
                <label class="control-label" for="signin"></label>
                <div class="controls">
                  <button id="signin" name="login" class="btn btn-success">Sign In</button>
                </div>
              </div>
              </fieldset>
              </form>
          </div>


          <div class="tab-pane fade" id="forgotpass">
              <form action="" method="post" class="form-horizontal" autocomplete="off">
                <?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<div class='alert alert-success' role='alert'>Your account is now active you may now log in.</div>";
							break;
						case 'reset':
							echo "<div class='alert alert-success' role='alert'>Check inbox for reset email</div>";
							break;
					}
				}
				?>
              <fieldset>
              <div class="control-group">
                <label class="control-label" for="useremail">Email Address:</label>
                <div class="controls">
                  <input required="" name="forgotemail" type="text" class="form-control" placeholder="you@domain.com" class="input-medium" required="">
                </div>
              </div>

              <!-- Button -->
              <div class="control-group">
                <label class="control-label" for="signin"></label>
                <div class="controls">
                  <button name="forgotsubmit" class="btn btn-success">E-mail Password Reset</button>
                </div>
              </div>
              </fieldset>
              </form>
          </div>

          <div class="tab-pane fade" id="signup">
              <form role="form" action="" method="post" autocomplete="off" class="form-horizontal">
              <fieldset>
              <!-- Sign Up Form -->
              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="Email">Email:</label>
                <div class="controls">
                  <input id="Email" name="email" class="form-control" type="text" placeholder="you@domain.com" class="input-large" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="userid">Username:</label>
                <div class="controls">
                  <input id="userid" name="username" class="form-control" type="text" placeholder="JohnDoe" class="input-large" required="">
                </div>
              </div>

              <!-- Password input-->
              <div class="control-group">
                <label class="control-label" for="password">Password:</label>
                <div class="controls">
                  <input id="password" name="password1" class="form-control" type="password" placeholder="********" class="input-large" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="reenterpassword">Re-Enter Password:</label>
                <div class="controls">
                  <input id="reenterpassword" class="form-control" name="password2" type="password" placeholder="********" class="input-large" required="">
                </div>
              </div>


              <div class="control-group">
                <label class="control-label" for="userid">First Name:</label>
                <div class="controls">
                  <input id="userid" name="firstname" class="form-control" type="text" placeholder="First Name" class="input-large" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="userid">Surname:</label>
                <div class="controls">
                  <input id="userid" name="surname" class="form-control" type="text" placeholder="Surname" class="input-large" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="userid">Date of Birth:</label>
                <div class="controls">
                  <input type="date" name="dateofbirth" class="form-control" class="input-large" required="">
                </div>
              </div>

              <!-- Text input-->
              <div class="control-group">
                <label class="control-label" for="userid">Telephone:</label>
                <div class="controls">
                  <input id="userid" name="phonenumber" class="form-control" type="text" placeholder="07111111111" class="input-large" required="">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="userid">Are you human?</label>
                <div class="controls">
                <div class="g-recaptcha" data-sitekey="6LeUxAcUAAAAAEzq-tzscA-xzBFDCdoE0nNtc1Ck"></div>
              </div>
            </div>


              <!-- Button -->
              <div class="control-group">
                <label class="control-label" for="confirmsignup"></label>
                <div class="controls">
                  <button id="confirmsignup" name="register" class="btn btn-success">Register</button>
                </div>
              </div>
              </fieldset>
              </form>
        </div>

      </div>
</div>

        <div class="modal-footer">
            <p>Not a member? <a href="#signup" data-toggle="tab">Sign Up</a></p>
            <p>Forgot <a href="#forgotpass" data-toggle="tab">Password?</a></p>
            <center>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </center>

        </div>
      </div>
    </div>
  </div>
  </body>
  </html>
