<html>
<head>
<link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <form class="form-horizontal" action="register.php" method="post" />
      <div class="form-group">

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
      <button type="submit" class="btn btn-default">Register</button>
    </div>
  </div>
</form>
  </div>
</body>
</html>
