<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Update Your Profile";

if(isset($_POST['updateuser'])) {

	$firstname = $_POST['firstname'];
	$surname = $_POST['surname'];
	$email = $_POST['email'];
	$phonenumber = $_POST['phonenumber'];
	$usertype = $_POST['usertype'];
	$department = $_POST['department'];

	$stmt = $db->prepare('UPDATE users SET firstname=:firstname, surname=:surname, email=:email, phonenumber=:phonenumber, usertype=:usertype, department=:department WHERE id = :userid');
	$stmt->bindParam(':userid', $userid, PDO::PARAM_STR,100);
	$stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR,100);
	$stmt->bindParam(':surname', $surname, PDO::PARAM_STR,100);
	$stmt->bindParam(':email', $email, PDO::PARAM_STR,100);
	$stmt->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR,100);
	$stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR,100);
	$stmt->bindParam(':department', $department, PDO::PARAM_STR,100);
	$stmt->execute();

	$updateuser = true;
}
;?>

<!-- Breadcrumbs -->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div id="result"></div>
		<div id="content">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active"><?php echo $pagename; ?></li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<?php if(isset($updateuser)) {
				echo "<div class='alert bg-success' role='alert'>
				<svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully updated user information<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
				</div>";
			} ?>
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<strong>Your Profile</strong>
							</div>
							<div class="panel-body">
								<?php
								if (isset($_POST['submit'])) {

								  if(strlen($_POST['password1']) < 8 ) {
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

								    try {

								      $stmt = $db->prepare('UPDATE users SET firstname=:firstname, surname=:surname, password=:password, email=:email, phonenumber=:phonenumber WHERE id=:userid');
								      //$stmt = $db->prepare('INSERT INTO users (username, firstname, surname, dateofbirth, password, email, phonenumber, department, usertype, creationdate) VALUES (:username, :firstname, :surname, :dateofbirth, :password, :email, :phonenumber, :department, :usertype, :creationdate)');
								      $stmt->execute(array(
								                          ':firstname' => $_POST['firstname'],
								                          ':surname' => $_POST['surname'],
								                          ':password' => $hashpassword,
								                          ':email' => $_POST['email'],
								                          ':phonenumber' => $_POST['phonenumber'],
								                          ':userid' => $_SESSION['id']
								                        ));
								      header('Location: profile.php?action=updated');
								    } catch(PDOException $e) {
								      $error[] = $e->getMessage();
								    }
								  }
								  }

								?>
								<div id="content">
								  <?php
								    // check for errors
								    if(isset($error)){
								      foreach($error as $error){
								        echo '<p class="bg-danger">' . $error . '</p>';
								      }
								    }

								    //if successfull
								    if(isset($_GET['action']) && $_GET['action'] == 'updated'){
								      echo "<div class='alert alert-success' role='alert'>Successfully updated profile</div>";
								    }

								   ?>
								<?php
								$loadprofile = $db->query("SELECT username, firstname, surname, password, email, phonenumber, department, usertype FROM users WHERE id = $_SESSION[id]");

								      echo "<form class='form-horizontal' method='post' action='' />";
								      echo "<div class='form-group'>
								      <label class='col-sm-2 control-label'>First Name</label>
								      <div class='col-sm-10'>";
								      while ($o = $loadprofile->fetch(PDO::FETCH_ASSOC)){
								        echo "<input class='form-control' name='firstname' placeholder='" . $o['firstname'] . "'></div></div>";
								        echo "<div class='form-group'><label class='col-sm-2 control-label'>Surname</label>
								              <div class='col-sm-10'><input class='form-control' name='surname' placeholder='" . $o['surname'] ."'></div></div>";
								        echo "<div class='form-group'><label class='col-sm-2 control-label'>Password</label>
								              <div class='col-sm-10'><input type='password' class='form-control' name='password1' placeholder='Password'></div></div>";
								        echo "<div class='form-group'><label class='col-sm-2 control-label'>Confirm Password</label>
								              <div class='col-sm-10'><input type='password' class='form-control' name='password2' placeholder='Verify Password'></div></div>";
								        echo "<div class='form-group'><label class='col-sm-2 control-label'>E-mail Address</label>
								              <div class='col-sm-10'><input class='form-control' name='email' placeholder='" . $o['email'] ."'></div></div>";
								        echo "<div class='form-group'><label class='col-sm-2 control-label'>Phonenumber</label>
								              <div class='col-sm-10'><input class='form-control' name='phonenumber' placeholder='" . $o['phonenumber'] ."'></div></div>";
								    }
								?>
								    <div class="form-group">
								      <div class="col-sm-offset-2 col-sm-10">
								        <button type="submit" class="btn btn-default" name="submit">Submit</button>
								      </div>
								    </div>
								  </form>
								</div>
							</div>
						</div>
				</div><!--/.row-->


			</div><!--/.main-->
		</div>

<? require_once ($_SERVER['DOCUMENT_ROOT'].'/config/scripts.php'); ?>
</body>

</html>
