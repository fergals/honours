<?php
include 'header.php'; ?>
<div id="tickets">

  <form action="submitsqli.php" method="post" />
  <p>
    Query: <input type="text" name="query" /><br />
    Status: <select name="status">
              <option value="open">Open</option>
              <option value="closed">Closed</option>
    </select>
  </p>
  <input type="submit" value="Submit" / />
</form>

</div>
<?php
include 'footer.php'; ?>
