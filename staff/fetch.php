<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
$output = '';
$stmt = $db->query("SELECT * FROM ticket WHERE query LIKE '%".$_POST["search"]."%'");
if(count($stmt) > 0)
{
     $output .= '	<div class="panel-heading">
     								<strong>Seach Results</strong></div>
     							<div class="panel-body">';
     $output .= '<div class="table table-striped">
                         <table class="table table bordered">
                              <tr>
                                   <th>Ticket ID</th>
                                   <th>Query</th>
                              </tr>';
     while($row = $stmt->fetch())
     {
          $output .= '
               <tr>
                    <td><a href="viewticket.php?id='. $row["tID"] .'">' . $row["tID"] . '</td>
                    <td>'.$row["query"].'</td>
               </tr>
          ';
     }
     echo $output;
}
else
{
     echo 'Data Not Found';
}
?>
