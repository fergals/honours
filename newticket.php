<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
      Please write your query below. Be as descriptive as possible and attach any files needed in order for staff members to action quickly.
    </div>
    <div class="span10">
      <form class="form-horizontal" action="submitticket.php" method="post" />
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
          <div class="col-sm-10">
            <textarea class='form-control' rows='5' name='query'></textarea>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
          </div>
        </div>
    </div>
  </div>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');?>
