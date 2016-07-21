<?php
include 'header.php'; ?>
  <form class="form-horizontal" action="submitsqli.php" method="post" />
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
      <div class="col-sm-10">
        <textarea class='form-control' rows='5' name='query'></textarea>
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">Staus</label>
      <div class="col-sm-10">
        <select name="status" class="form-control">
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
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
