<script>
function selectionchange()
{
    var e = document.getElementById("catoptions");
    var str = e.options[e.selectedIndex].value;

    document.getElementById('editcat','hiddencatid').value = str;
    document.getElementById('hiddencatid').value = str;
}</script>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

$date = date("Y-m-d H:i:s");
$depid = $_SESSION['id'];

if(isset($_POST['submitnewdep'])) {
  $departmentname = $_POST['depname'];
  $userid = $_SESSION['id'];
  $stmt = $db->prepare("INSERT INTO departments (department, datecreated, userid) VALUES (:department, :datecreated, :userid)");
  $stmt->bindParam(':department', $departmentname, PDO::PARAM_STR,100);
  $stmt->bindParam(':datecreated', $date, PDO::PARAM_STR, 10000);
  $stmt->bindParam(':userid', $depid, PDO::PARAM_INT, 3);
  $stmt->execute();
}
?>


<div id="content">
<h2>Add Categories</h2>

<form class="form-inline" action="submitcat.php" method="post" />
  <div class="form-group">
    <label for="exampleInputName2">Name of Category </label>
    <input type="text" class="form-control" name="categoryname" id="categoryname">
  </div>
  <button type="submit" class="btn btn-default" name="newcat" id="newcat">Add Category</button>
</form>

<h2>Edit Categories</h2>
<?php
$editcat = $db->query("SELECT category FROM categories ORDER BY category");
echo "<select multiple class='form-control' size='10' id='catoptions' onchange='selectionchange();'>";
while ($ec = $editcat->fetch(PDO::FETCH_ASSOC)){
echo "<option>" . $ec['category'] . "</option>";
}
echo "</select>";
?>
<br />
<form class="form-inline" action="submitcat.php" method="post" />
  <div class="form-group">
    <label>Edit</label>
    <input type="text" class="form-control" name="editcat" id="editcat">
    <input type="hidden" name="hiddencatid" id="hiddencatid" />
  </div>
  <button type="submit" class="btn btn-default">Edit Category Name</button>
  <button type="submit" class="btn btn-danger">Delete Category</button>
</form>


<h2>Add Departent</h2>

<form class="form-inline" action="" method="post" />
  <div class="form-group">
    <label for="exampleInputName2">Name of Department</label>
    <input type="text" class="form-control" name="depname" id="depname">
  </div>
  <button type="submit" class="btn btn-default" name="submitnewdep" id="newdep">Add Department</button>
</form>

<h2>Edit Department</h2>
<?php
$editdep = $db->query("SELECT department FROM departments ORDER BY department");
echo "<select multiple class='form-control' size='10' id='depoptions' onchange='selectionchange();'>";
while ($ed = $editdep->fetch(PDO::FETCH_ASSOC)){
echo "<option>" . $ed['department'] . "</option>";
}
echo "</select>";
?>
<br />
<form class="form-inline" action="submitdep.php" method="post" />
  <div class="form-group">
    <label>Edit</label>
    <input type="text" class="form-control" name="editdep" id="editdep">
    <input type="hidden" name="hiddencatid" id="hiddencatid" />
  </div>
  <button type="submit" class="btn btn-default">Edit Category Name</button>
  <button type="submit" class="btn btn-danger">Delete Category</button>
</form>
</div>
<?php include '../template/adminfooter.php'; ?>
