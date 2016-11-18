<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if (isset($_GET['term'])){
    $return_arr = array();

    try {
        $stmt = $db->prepare('SELECT id, firstname, surname, department, email FROM users WHERE firstname LIKE :term');
        $stmt->execute(array('term' => '%'.$_GET['term'].'%'));

        while($row = $stmt->fetch()) {
          $array = array (
            'label' => $row['firstname'] . " " . $row['surname'],
            'value' => $row['firstname'],
            'surname' => $row['surname'],
            'email' => $row['email'],
            'department' => $row['department']
          );
        }

    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }


    echo json_encode($array);
}

// $(document).ready(function() {
// $('#firstname').autocomplete({
//        minLength: 0,
//        source: 'search.php?term'+$(this).val(),
//        datatype: 'json',
//        select: function( event, ui ) {
//              // insert additional texts in input fields
//              console.log(ui.item.data);
//          //$('#firstname').val(ui.item.data.firstname);
//          $('#surname').val(ui.item.data.surname);
//          $('#department').val(ui.item.data.department);
//        }
//   });
//   });

?>
