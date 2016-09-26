<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

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
<h1>Edit your profile</h1><br>

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
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php'); ?>
