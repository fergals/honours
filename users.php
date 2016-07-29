<?php
include 'header.php'; ?>
<strong>Users should be displayed here</strong><hr />

<strong>Add User</strong><br /><br />
<form action="submituser.php" method="post" />

<div class="form-group">
  <label>Username</label>
  <input type="text" class="form-control" name="username">
</div>

<div class="form-group">
  <label>Firstname</label>
  <input type="text" class="form-control" name="firstname">
</div>

<div class="form-group">
  <label>Surname</label>
  <input type="text" class="form-control" name="surname">
</div>

<div class="form-group">
  <label>Password</label>
  <input type="text" class="form-control" name="password">
</div>

<div class="form-group">
  <label>Email Address</label>
  <input type="text" class="form-control" name="email">
</div>

<div class="form-group">
  <label>Telephone Number</label>
  <input type="text" class="form-control" name="phonenumber">
</div>

<div class="form-group">
  <label>Date of Birth</label>
  <input type="date" class="form-control" name="dateofbirth">
</div>

<div class="form-group">
  <label>Department</label>
  <select name="department" class="form-control">
    <option value="Accounts">Accounts</option>
    <option value="Admissions">Admissions</option>
    <option value="Computing">Computing</option>
    <option value="Finance">Finance</option>
    <option value="Marketing">Marketing</option>
    <option value="Student">Student Issues</option>
  </select>
</div>

<div class="form-group">
  <label>User Type</label>
  <select name="usertype" class="form-control">
    <option value="Admin">Administrator</option>
    <option value="Manager">Manager</option>
    <option value="Operator">Operator</option>
    <option value="Registered">Registered User</option>
  </select>
</div>
<button type="submit" class="btn btn-default">Submit</button>
</form>

</div>
<?php
include 'footer.php'; ?>
