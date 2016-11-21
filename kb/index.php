<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $pagetitle ?> - Knowledge Base</title>

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/styles.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="/js/bootstrap.js"></script>
<!--Icons-->
<script src="/js/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="../js/html5shiv.js"></script>
<script src="../js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><span>HELP!</span>Ticket_management</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="" data-toggle="modal" data-target="#loginmodal">Login / Register</span></a>
					</li>
				</ul>
			</div>

		</div><!-- /.container-fluid -->
	</nav>
<div class="searchcontainer">
	<div id="searchheader" class="searchheader">
		<div id="searchheader" class="col-sm-6 col-sm-offset-3">
		<div id="imaginary_container">
				<div class="input-group stylish-input-group">
						<input type="text" class="form-control"  placeholder="Search" >
						<span class="input-group-addon">
								<button type="submit">
										<span class="glyphicon glyphicon-search"></span>
								</button>
						</span>
				</div>
		</div>
	</div>
</div>
</div>

<div class="container">
	<div class="row">
			<div class="col-md-4">
				<div>
          <h2>Featured Articles</h2>
					<div>
						Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
					</div>
          <div>
            Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
          </div>
          <div>
            Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
          </div>
				</div>
			</div>

      <div class="col-md-4">
				<div>
          <h2>Latest Articles</h2>
					<div>
						Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
					</div>
          <div>
            Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
          </div>
          <div>
            Can't install the Operating System<br>
            23 Feb 2013 in <a href="as">Computer Issues</a><br><br>
          </div>
				</div>
			</div>

      <div class="col-md-4">

			</div>


		</div><!-- /.row -->


	</body>
	</html>
