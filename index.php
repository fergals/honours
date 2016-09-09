<?php
//require_once '/config/dbconnect.php';
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
?>

<div class="indexwrapper">
  <div id="content">
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
<div class="registration col-md-10">
    <form class="form-horizontal" action="" method="post" autocomplete="off"/>
      <div class="form-group">

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pagetitle; ?></title>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/assets/font-awesome/css/font-awesome.min.css">
		    <link rel="stylesheet" href="/css/assets/css/form-elements.css">
        <link rel="stylesheet" href="/css/assets/css/style.css">
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    </head>
<body>
        <!-- Top content -->
        <div class="top-content">
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1>HELP! Online Support</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                        	<div class="form-box">
	                        	<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Login HELP!</h3>
	                            		<p>Enter username and password to log on:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-key"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" action="" method="post" class="login-form">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-username">Username</label>
				                        	<input type="text" name="loginusername" placeholder="Username..." class="form-username form-control" id="form-username">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-password">Password</label>
				                        	<input type="password" name="loginpassword" placeholder="Password..." class="form-password form-control" id="form-password">
				                        </div>
				                        <button type="submit" class="btn" name="login">Login</button>
				                    </form>
			                    </div>
		                    </div>

                        </div>

                        <div class="col-sm-1 middle-border"></div>
                        <div class="col-sm-1"></div>

                        <div class="col-sm-5">

                        	<div class="form-box">
                        		<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Sign up now</h3>
	                            		<p>Fill in the form below to get instant access:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-pencil"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" action="" method="post" class="registration-form" autocomplete="off">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-first-name">First name</label>
				                        	<input type="text" name="firstname" placeholder="First name..." class="form-first-name form-control" id="form-first-name">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-last-name">Last name</label>
				                        	<input type="text" name="surname" placeholder="Last name..." class="form-last-name form-control" id="form-last-name">
				                        </div>

                                <div class="form-group">
                                  <label class="sr-only" for="form-last-name">Last name</label>
                                  <input type="text" name="username" placeholder="Username..." class="form-last-name form-control" id="form-last-name">
                                </div>

				                        <div class="form-group">
				                        	<label class="sr-only" for="form-email">Password</label>
				                        	<input type="password" name="password1" placeholder="Password..." class="form-email form-control" id="form-password1">
				                        </div>

                                <div class="form-group">
                                  <label class="sr-only" for="form-email">Password</label>
                                  <input type="password" name="password2" placeholder="Confirm Password..." class="form-email form-control" id="form-password2">
                                </div>

                                <div class="form-group">
                                  <label class="sr-only" for="form-email">Email</label>
                                  <input type="text" name="email" placeholder="Email..." class="form-email form-control" id="form-email">
                                </div>

                                <div class="form-group">
                                  <label class="sr-only" for="form-email">Telephone</label>
                                  <input type="text" name="phonenumber" placeholder="Telephone Number..." class="form-email form-control" id="form-telephone">
                                </div>

                                <div class="form-group">
                                  <label class="sr-only" for="form-email">Date of Birth...</label>
                                  <input type="date" name="dateofbirth" placeholder="Date of Birth..." class="form-email form-control" id="form-dob">
                                </div>
				                        <button type="submit" class="btn" name="register">Register</button>
				                    </form>
			                    </div>
                        	</div>

                        </div>
                    </div>

                </div>
            </div>

        </div>


        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/scripts.js"></script>

        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>
