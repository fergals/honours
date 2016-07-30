<?php
include 'header.php'; ?>
  <form class="form-horizontal" action="submitticket.php" method="post" />
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
      <div class="col-sm-10">
        <textarea class='form-control' rows='5' name='query'></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-2 control-label">Staus</label>
      <div class="col-sm-10">
        <select name="status" class="form-control">
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="queue" class="col-sm-2 control-label">Queue</label>
      <div class="col-sm-10">
        <select name="queue" class="form-control">
          <option value="1stline">1st Line</option>
          <option value="2ndline">2nd Line</option>
          <option value="3rdline">3rd Line</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="urgency" class="col-sm-2 control-label">Urgency</label>
      <div class="col-sm-10">
        <select name="urgency" class="form-control">
          <option value="low">Low</option>
          <option value="normal">Normal</option>
          <option value="critical">Crtical</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="department" class="col-sm-2 control-label">Departments</label>
      <div class="col-sm-10">
        <select name="department" class="form-control">
          <option value="Accounts">Accounts</option>
          <option value="Admissions">Admissions</option>
          <option value="Computing">Computing</option>
          <option value="Finance">Finance</option>
          <option value="Marketing">Marketing</option>
          <option value="Student">Student Issues</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="category" class="col-sm-2 control-label">Category</label>
      <div class="col-sm-10">
        <select name="category" class="form-control">
          <option value="Computing">Computing</option>
          <option value="Money Issues">Money Issues</option>
        </select>
      </div>
    </div>


    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>


</div>
<?php
include 'footer.php'; ?>
