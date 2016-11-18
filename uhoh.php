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

    $stmt = $db->prepare('INSERT INTO users (username, firstname, surname, password, email, phonenumber, department, usertype, creationdate, active) VALUES (:username, :firstname, :surname, :password, :email, :phonenumber, :department, :usertype, :creationdate, :active)');
    $stmt->execute(array(
                          ':username' => $_POST['username'],
                          ':firstname' => $_POST['firstname'],
                          ':surname' => $_POST['surname'],
                          ':password' => $hashpassword,
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
			$mail->setFrom(SITEMAIL);
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
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $pagetitle ?></title>

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="/js/bootstrap.js"></script>
<!--Icons-->
<script src="/js/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="../js/html5shiv.js"></script>
<script src="../js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>HELP!</span>Ticket_management</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="" data-toggle="modal" data-target="#loginmodal">Login / Register</span></a>
					</li>
				</ul>
			</div>

		</div><!-- /.container-fluid -->
	</nav>
	<?php
		// check for errors
		if(isset($error)){
			foreach($error as $error){
				echo "<div class='alert bg-warning' role='alert'>
					<svg class='glyph stroked flag'><use xlink:href='#stroked-flag'></use></svg>" . $error . "<a href='#' class='pull-right' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span></a>
				</div>";
			}
		}

		//if successfull
		if(isset($_GET['action']) && $_GET['action'] == 'joined'){
			echo "<div class='alert bg-success' role='alert'>
					<svg class='glyph stroked checkmark'><use xlink:href='#stroked-checkmark'></use></svg> Successfully Registered<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
				</div>";
		}

	 ?>

<div class="container">
	<div class="row">
    <div class="alert bg-danger" role="alert">
      <svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg>You are not authorised to view this page. Please login or register <a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
			<div class="col-lg-12">
				<h2>Need Help?</h2>
			</div>
			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						Frequently Asked Questions
					</div>
					<div class="panel-body">
						<p>Have you tried searching for your answer in our KnowledgeBase?</p> <p>Our most frequently asked questions are displayed here to allow for a quick resolution.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						Submit a ticket
					</div>
					<div class="panel-body">
						<p>If you are unable to find your answer in our Knowledgebase, please login and submit to a ticket.</p>
						<p>The ticket will be submitted to the correct department and reply to you quickly</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						E-mail
					</div>
					<div class="panel-body">
						<p>Tickets can be submitted from your own e-mail client by e-mailing EMAIL ADDRESS</p>
						<p>Please ensure you have provided as much information as possible</p>
					</div>
				</div>
			</div>

		</div><!-- /.row -->

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
	                  <input id="Email" name="email" class="form-control" type="text" placeholder="Your email address" class="input-large" required="">
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
	                <label class="control-label" for="reenterpassword">Verify Password:</label>
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
	                <label class="control-label" for="userid">Telephone:</label>
	                <div class="controls">
	                  <input id="userid" name="phonenumber" class="form-control" type="text" placeholder="07111111111" class="input-large" required="">
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
	            <p><a href="#signup" data-toggle="tab">Not Registered?</a></p>
	            <p><a href="#forgotpass" data-toggle="tab">Forgot Password?</a></p>
	            <center>
	              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </center>

	        </div>
	      </div>
	    </div>
	  </div>

	</body>
	</html>
