<link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

//check if user is logged in, if so redirect to index.php
  if ($user->is_logged_in() ) {
    header('Location: main.php');
  }

//if the form has been submitted then submit it
  if(isset($_POST['submit'])) {

//begin registartion form validation
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
  $department = "Student";
  $usertype = "Registered";
  $creationdate = date("Y-m-d H:i:s");

  try {

    $stmt = $db->prepare('INSERT INTO users (username, firstname, surname, dateofbirth, password, email, phonenumber, department, usertype, creationdate) VALUES (:username, :firstname, :surname, :dateofbirth, :password, :email, :phonenumber, :department, :usertype, :creationdate)');
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
                        ':creationdate' => $creationdate
                      ));
    header('Location: main.php?action=joined');
  } catch(PDOException $e) {
    $error[] = $e->getMessage();
  }
}
}
?>

<div class="indexwrapper">

<div class="registration col-md-10">
    <form class="form-horizontal" action="" method="post" autocomplete="off"/>
      <div class="form-group">
      <?php
        // check for errors
        if(isset($error)){
          foreach($error as $error){
            echo '<p class="bg-danger">' . $error . '</p>';
          }
        }

        //if successfull
        if(isset($_GET['action']) && $_GET['action'] == 'joined'){
          echo "<h2 class='bg-success'>Registration Successful</h2>";
        }

       ?>

        <label class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="username" required>
        </div>

      <label class="col-sm-2 control-label">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="password1" required>
      </div>

    <label class="col-sm-2 control-label">Re-enter Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password2" required>
    </div>

    <label class="col-sm-2 control-label">Firstname</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="firstname" required>
    </div>

    <label class="col-sm-2 control-label">Surname</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="surname" required>
    </div>

  <label class="col-sm-2 control-label">Email</label>
  <div class="col-sm-10">
    <input type="email" class="form-control" name="email" required>
  </div>

  <label class="col-sm-2 control-label">Date of Birth</label>
  <div class="col-sm-10">
    <input type="date" class="form-control" name="dateofbirth" required>
  </div>

  <label class="col-sm-2 control-label">Telephone</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="phonenumber" required>
  </div>


  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" class="btn btn-default">Register</button>
    </div>
  </div>
</form>
  </div>
</div>
</body>
</html>
