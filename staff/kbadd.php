<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Add Article to Knowledge Base";
$userid = $_SESSION['id'];



if(isset($_POST['submitarticle'])) {
  $article = $_POST['article'];
  $title = $_POST['title'];
  $dateadded = $current_date;
  $category = $_POST['category'];
  $tags = $_POST['tags'];

  $stmt = $db->prepare("INSERT INTO kbarticles (title, body, tags, category, dateadded, userid) VALUES (:title, :article, :tags, :category, :dateadded, :userid)");
  $stmt->bindParam(':article', $article, PDO::PARAM_INT, 10000);
  $stmt->bindParam(':title', $title, PDO::PARAM_INT, 200);
  $stmt->bindParam(':dateadded', $dateadded, PDO::PARAM_STR, 30);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR, 50);
  $stmt->bindParam(':tags', $tags, PDO::PARAM_STR, 200);
  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 3);

  $stmt->execute();

  if(empty($category)){
      $categorye = true;
      $categoryerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please select a category<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }

  if(empty($title)){
      $titlee = true;
      $titleerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please enter a title for the article<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    if(empty($article)){
        $articlee = true;
        $articleerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please enter information for the article<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
      }
      if(empty($category)){
          $categorye = true;
          $categoryerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please select a category<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
        }
    else {
      $articlesuc= true;
      $articlesuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully added new Article to Knowledge Base<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
}
  ?>
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
      <?php
      if(isset($titlee)) {echo $titleerror;}
      if(isset($articlee)) {echo $articleerror;}
      if(isset($articlesuc)) {echo $articlesuccess;}
      if(isset($categorye)) {echo $categoryerror;}
      ?>
      <form method="post" action="">
			<div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-two-messages"></use></svg> Add New Article to Knowledge Base</div>
					<div class="panel-body">

            <div class="form-group">
              <input class="form-control" name="title" placeholder="Title for Article">
            </div>

            <div class="form-group">
              <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
              //<![CDATA[
              bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
              //]]>
              </script>

              <textarea class="form-control" rows="15" name="article" placeholder="Write the full article here"></textarea>
            </div>
            <button class="btn btn-primary pull-right" name="submitarticle" type="submit">Submit Article to Knowledge Base</button>

					</div>

				</div>

			</div><!--/.col-->

			<div class="col-md-4">
        <div class="panel panel-info">
          <div class="panel-heading"><svg class="glyph stroked calendar"></svg>Article Details</div>
          <div class="panel-body">
            <?php
            $stmt = $db->prepare("SELECT categoryid, category FROM kbcategory ");
            $stmt->execute();
            echo "<label>Category</label>";
            echo "<select name='category' class='form-control'>";
            echo "<option disabled selected value>Select a category</option>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['categoryid'] . "'>" . $row['category'] ."</option>";
            }
            echo "</select>";

            $stmt = $db->prepare("SELECT urgency FROM urgency");
            $stmt->execute();
            echo "<label>Tags</label>";
            echo "<select name='tags' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['urgency'] . "'>" . $row['urgency'] ."</option>";
            }
            echo "</select>";
          ?>

          </div>
        </div>
              </form>

			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->


</div>
</body>

</html>
