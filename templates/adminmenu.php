<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <form role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search" id="search_text">
    </div>
  </form>
  <nav id="nav">
  <ul class="nav menu">
    <li class="active"><a href="index.php"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Dashboard</a></li>
    <li><a href="unassigned.php"><svg class="glyph stroked calendar"><use xlink:href="#stroked-star"></use></svg> Unassigned Queue</a></li>
    <li><a href="depqueue.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg><?php echo $_SESSION['department'] . "'s Queue"; ?></a></li>
    <li><a href="newticket.php"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg> New Ticket</a></li>

    <li class="parent ">
      <a href="#">
        <span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></use></svg></span> Knowledge Base
      </a>
      <ul class="children collapse" id="sub-item-1">
        <li>
          <a class="" href="kbadd.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Add Article
          </a>
        </li>
        <li>
          <a class="" href="kb.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> View Articles
          </a>
        </li>
        <li>
          <a class="" href="kbmanage.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Manage Categories
          </a>
        </li>
      </ul>
    </li>

    <li class="parent ">
      <a href="#">
        <span data-toggle="collapse" href="#sub-item-1"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></use></svg></span> Administration
      </a>
      <ul class="children collapse" id="sub-item-1">
        <li>
          <a class="" href="categories.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Categories
          </a>
        </li>
        <li>
          <a class="" href="departments.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Departments
          </a>
        </li>
        <li>
          <a class="" href="users.php">
            <svg class="glyph stroked chevron-right"><use xlink:href="#stroked-chevron-right"></use></svg> Users
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>
</div><!--/.sidebar-->
<script type="text/javascript">
$(document).ready(function(){
     $('#search_text').keyup(function(){
          var txt = $(this).val();
          if(txt != '')
          {
               $.ajax({
                    url:"fetch.php",
                    method:"post",
                    data:{search:txt},
                    dataType:"text",
                    success:function(data)
                    {
                        $('#content').hide();
                         $('#result').html(data);

                    }
               });
          }
          else
          {
               $('#result').html('');
          }
     });
});

// $(function() {
//
//     //autocomplete
//     $(".auto").autocomplete({
//         source: "search.php",
//         minLength: 1
//     });
//
// });
</script>
