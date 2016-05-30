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

  $card = new Datadigital(); 

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

 
?>

<h1 style="color: #7d3589; background-color: #ccc;"><?php echo trim($req->title); ?></h1>


<div style="margin-top: 20px" class="row">
  <div class="col-md-6">
    <p style="text-align: left;margin-top: 20px;line-height: 30px"> 
      <?php echo trim($req->description); ?> 
    </p>
  </div>
  
  <div class="col-md-6">
    <?php
    /*
      if( !empty($req->image) ){
        echo '<img  src="../wp-admin/_uploads/'.$req->image.'">';
    
    }
    */ 
    ?>   
  </div>
  </div>

<div class="row">

  <div style="" class="col-md-4">
  <h3 style="background: rgba( 0, 0, 0, 0.5);font-size: 20px;text-align:left; padding: 20px;color: #fff; line-height: 20px;">  
        Origine <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->origine); ?> <br>
        Age <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->age); ?>
  </h3>
  </div>

  <div class="col-md-4">
  <h3 style="background: rgba(87, 2, 90, 0.5);font-size: 20px;text-align:left; padding: 20px;color: #fff; line-height: 20px;">
        Thematique <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->thematique); ?> <br>
        Potentiel <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->potentiel); ?> 
  </h3>
  </div>

  <div  class="col-md-4">
  <h3 style="background: rgba( 0, 0, 0, 0.5);font-size: 20px;text-align:left; padding: 20px;color: #fff; line-height: 20px;">
        Repartition <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->repartition); ?> <br>
        MMC <i class="fa fa-arrow-circle-o-right"></i> <?php echo trim($req->mmc); ?>
  </h3>
  </div>

</div>


</div>