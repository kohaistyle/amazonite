<?php

require_once 'wp-admin/_amz_datas//ActiveRecord.php';
require 'wp-admin/_amz_datas//ActiveRecord.model.digital.php';
require 'wp-admin/_amz_datas//ActiveRecord.model.php';

function arToJson($data, $options = null) {
$out = "[";
foreach( $data as $row) { 
if ($options != null)
$out .= $row->to_json($options);
else 
$out .= $row->to_json();
$out .= ",";
}
$out = rtrim($out, ',');
$out .= "]";
return $out;
}




  $id = 1;
  $id = filter_input( INPUT_GET, 'id' );

  if($id == null){
    echo "<h2>Error in Url</h2>";
    echo "<p> <a href='_amz_list_postal.php'><<-- Get back to list</a> </p>";
    die();
  } 

  $card = new Datacard(); 

  try
  {
    $req = $card::find($id);
  }
  catch (Exception $e)
  {
    echo "<h2>Database error</h2>";
    echo "<p> There was an error connecing to database. Please go back and retry ! </p>";
    echo $e->getMessage();
    die();
  }


  $postal = explode( "##", $req->ratepostal);
  $colis = explode( "##", $req->ratecolis);
  $email = explode( "##", $req->ratemail);

  
?>

<style type="text/css">
<!--
 tr{ height: 48px; }
 
 #table_id tr:first-child {
  background-color: #7d3589 ! important;
  font-weight: bold;
 }
 #table_id thead{ display: none}
 
//-->
</style>

<h1 style="color: #7d3589; background-color: #ccc;"><?php echo trim($req->title); ?></h1>
<div class="row">
  <div class="col-md-6"><p style="text-align: left;"> <?php echo trim($req->descript); ?> </p></div>

  <div class="col-md-6">
        <pre> <?php echo trim($req->info); ?> </pre>
  </div>


</div>



<div class="row">
    <div class="col-md-6">
      <table id="table_id" class="display">
      <thead>
      <tr>
      <th>thisid</th>
      <th>Head</th>
      <th>Type</th>
      <th>Column 1</th>
      <th>Column 2</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td>Row 1 Data 1</td>
      <td>Row 1 Data 1</td>
      <td>Row 1 Data 2</td>
      <td>Row 1 Data 2</td>
      <td>Row 1 Data 2</td>
      </tr>
      </tbody>
      </table>
    </div>

  <div class="col-md-6"> 

      <table id="table_rate" class="display">
      <thead>
      <tr>
      <th></th>
      <th>Prix de base<br>Baserate</th>
      <th>Frais de livraison<br>Delivery Fee</th>
      <th>Frais de sélection<br>Selection Fee</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td>Postal</td>
      <td><?php echo $postal[0]; ?></td>
      <td><?php echo $postal[1]; ?></td>
      <td><?php echo $postal[2]; ?></td>

      </tr>
      <tr>
      <td>Asile Colis</td>
      <td><?php echo $colis[0]; ?></td>
      <td><?php echo $colis[1]; ?></td>
      <td><?php echo $colis[2]; ?></td>

      </tr>
      <tr>
      <td>Email</td>
      <td><?php echo $email[0]; ?></td>
      <td><?php echo $email[1]; ?></td>
      <td><?php echo $email[2]; ?></td>

      </tr>
      </tbody>
      </table>

      <br>
      <img class="alignnone size-full" src="http://huggy.info/amazonite/wp-admin/_uploads/<?php echo trim($req->logo); ?>" alt="logo" />


  </div>

</div>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="../_dataTables/media/css/jquery.dataTables.css" />
<link rel="stylesheet" href="../_dataTables/extensions/TableTools/css/dataTables.tableTools.css" />
<script src="../_dataTables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="../_dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>

<script>

$( document ).ready(function() {

 var table;

      table = $('#table_id').DataTable( {
        "processing": false,
        "serverSide": false,
        "paging":   false,
        "ordering": false,
        "searching": false,
        "info": false,
        
        //"ajax": "<?php echo $filename; ?>.json",
        "data":<?php echo trim($req->countryfile); ?>,
        //"sAjaxDataProp":"",
        "columnDefs": [
            {
                "targets": 0,
                "visible": false,
                "datas": "thisid",
                "searchable": true,
            }],
        "columns": [
            { "data": "thisid"},
            { "data": "head"},
            { "data": "type" },
            { "data": "column 1"},
            { "data": "column 2" }
        ]
      } );

});
    
</script>