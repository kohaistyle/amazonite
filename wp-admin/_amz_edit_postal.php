<?php

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

require './_amz_datas/ActiveRecord.php';
require './_amz_datas/ActiveRecord.model.php';

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
    echo "<p> <a href='_amz_list_postal.php'><<-- Get back to list</a> </p>";
    echo $e->getMessage();
    die();
  }


  $ratepostal = explode( "##", $req->ratepostal );
  $ratecolis = explode( "##", $req->ratecolis );
  $ratemail = explode( "##", $req->ratemail );
  
  //print_r($req);
  
?>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

<!-- include libraries BS3 -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css" />
<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />

  <!-- include summernote -->
  <link rel="stylesheet" href="_summernote/summernote.css">
  <script type="text/javascript" src="_summernote/summernote.js"></script>

<!-- Css Framework -->
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0-rc-1/grids-min.css" type="text/css">
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0-rc-1/forms-min.css" type="text/css">
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0-rc-1/buttons-min.css" type="text/css">
<link rel="stylesheet" href="css/amz.buttons.css" type="text/css">
 
<!-- Alert plugin --> 
<script src="_vex/js/vex.combined.min.js"></script>
<script>vex.defaultOptions.className = 'vex-theme-plain';</script>
<link rel="stylesheet" href="_vex/css/vex.css" />
<link rel="stylesheet" href="_vex/css/vex-theme-plain.css" />

<!-- Notification plugin --> 
<script src="_notifyjs/notify.min.js"></script>
<script src="_notifyjs/styles/bootstrap/notify-bootstrap.js"></script>

<!-- Validation plugin -->
<script src="_validatejs/jquery.validate.min.js"></script>

<!-- Upload Plugin -->
<script src="_uploads/SimpleAjaxUploader.min.js"></script>

<!-- JQuery.Datatables -->
<link rel="stylesheet" href="_dataTables/media/css/jquery.dataTables.css" />
<link rel="stylesheet" href="_dataTables/extensions/TableTools/css/dataTables.tableTools.css" />
<script src="_dataTables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="_dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<script type="text/javascript" language="javascript" src="_dataTables/extensions/Editor/js/dataTables.editor.js"></script>


<!-- Rich Text Editor -->
  <script type="text/javascript">

var table=0;
var editor=0;

function submitForm(){

    //alert( document.getElementById("countrydata").value );

    
    $('textarea[name="datadesc"]').html($('#datadesc').code());
    $('textarea[name="datainfo"]').html($('#datainfo').code());
    
    var formdata =  $( '#myform' ).serialize() ;    
    console.log (formdata);

    //$.notify("getting infos",{ position:"bottom center", className:"info"} ).delay( 100 );

        var request  = $.ajax({ 
          type: "POST",
          data: formdata, 
          url:"_amz_datas/datacard_edit.php" 
        });
        
        request.done(function( msg ) {
        
          var reqmsg = '';
          
          switch (msg)
          {
            case 'success': 
              reqmsg = "Data updated successfully !";
              break;
            default: 
              reqmsg = "Error Adding data ("+ msg +" )";
              break;
          }
          $.notify(reqmsg,{ position:"bottom center", className:"info"} );
        });
        
        request.fail(function( jqXHR, textStatus ) {
          $.notify(textStatus,{ position:"bottom center", className:"error"} );
        });


}

  
    $(function() {


      $('#submitbutton').on('click', function (e) {
        //alert($('.summernote').code());
        e.preventDefault();

        var form = $( "#myform" );
        form.validate();
  
        if(form.valid())
          submitForm();
        else
          $.notify("Missing Fields !" ,{ position:"bottom center", className:"error"} ); 
  
              
      });

      var infobox = document.getElementById('infobox'), // container for file size info
          filebox = document.getElementById('filebox'), // container for file size info
          progress = document.getElementById('progress'); // the element we're using for a progress bar
            
      var uploader = new ss.SimpleUpload({
            button: 'upload-btn', // file upload button
            url: '_uploads/extras/uploadhandler.php', // server side handler
            name: 'uploadfile', // upload parameter name        
            progressUrl: '_uploads/extras/uploadProgress.php', // enables cross-browser progress support (more info below)
            responseType: 'json',
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            maxSize: 1024, // kilobytes
            hoverClass: 'ui-state-hover',
            focusClass: 'ui-state-focus',
            disabledClass: 'ui-state-disabled',
            onExtError: function(filename, extension) {
                $.notify("Wrong Filetype" ,{ position:"bottom right", className:"error"} ); 
              },
              onSizeError: function(filename, fileSize) {
                $.notify("Size Limit exceeded" ,{ position:"bottom right", className:"error"} ); 
              },
            onSubmit: function(filename, extension) {
                //this.setFileSizeBox(sizeBox); // designate this element as file size container
                this.setProgressBar(progress); // designate as progress bar
                infobox.innerHTML = 'uploading file ...';
              },         
            onComplete: function(filename, response) {
                if (!response) {
                    alert(filename + 'upload failed');
                    return false;            
                }
                if (response.success === true) {
                    infobox.innerHTML = '<img width="100" height="50" src="./_uploads/' + encodeURIComponent(response.file) + '">';
                    filebox.value = encodeURIComponent(response.file);
                    $.notify("Upload Completed",{ position:"bottom center", className:"success"} );                            
                  } else {
                    if (response.msg)  {
                      infobox.innerHTML = response.msg;
                      $.notify(response.msg,{ position:"bottom center", className:"error"} );   
                    } else {
                      infobox.innerHTML = 'Unable to upload file';
                      $.notify("Upload Error" ,{ position:"bottom center", className:"error"} ); 
                    }
                  }
                // do something with response...
              }
      });

<?php

  $file = md5(uniqid(rand(), true));
  $json = '{"data":'.trim($req->countryfile).'}';

  $filename = $file.".json";
  chmod($filename, 777);
  $fp = fopen( $filename, 'w+');
  fwrite($fp, $json);
  
  fclose($fp); 
 

?>


      //var countrydata = '{"data":<?php echo trim($req->countryfile); ?>}';

      table = $('#table_id').DataTable( {
        "processing": false,
        "serverSide": false,
        "paging":   false,
        "ordering": false,
        "searching": false,
        "info": false,
        
        "ajax": "<?php echo $filename; ?>",
        //"data":<?php echo trim($req->countryfile); ?>,
        //"sAjaxDataProp":"",
        "columnDefs": [
            {
                "targets": 0,
                "visible": true,
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

      editor = new $.fn.dataTable.Editor( {
              ajax: "_amz_datas/echo.php",
              table: "#table_id",
              fields: [ {
                      label: "thisid:",
                      name: "thisid"
                  },{
                      label: "head:",
                      name: "head"
                  }, {
                      label: "type:",
                      name: "type"
                  }, {
                      label: "column 1:",
                      name: "column 1"
                  }, {
                      label: "column 2:",
                      name: "column 2"
                  }
              ]
          } );

      editor.on( 'submitComplete', function ( e, type ) {
          // Type is 'main', 'bubble' or 'inline'
          //alert( 'Editor '+type+' form shown' );
          var countryvalue = JSON.stringify( table.data().toArray());
          document.getElementById("countrydata").value = countryvalue ;
      } );

      
      //$('#table_id').on( 'click', 'tbody td:not(:first-child)', function (e) {
      $('#table_id').on( 'click', 'tbody td', function (e) {
        editor.inline( this, {
            submitOnBlur: true
        } );
      } );

      
    });
  </script>
  
  

<form id="myform" action="#" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <h1>AMAZONITE <small>Datacard <b>Postal</b> ( update #<?php echo $id; ?>)</small></h1>

<!-- <button class="pure-button button-success button-xlarge">Francais</button> -->
<!-- <button class="pure-button button-success button-xlarge">English</button> -->

<input value="<?php echo $id; ?>" type="hidden" id="cardid" name="cardid">
<input value='<?php echo trim($req->countryfile); ?>' type="hidden" id="countrydata" name="countrydata">
<input value="blah" type="hidden" id="jsonfile" name="jsonfile">


<div class="pure-control-group">
        <label for="title">Titre</label>
        <input id="title" name="title" type="text" class="pure-input-1-2" value="<?php echo trim($req->title); ?>" placeholder="Datacard title" required>

<legend>Type</legend>   
        <div class="pure-control-group">
                <label for="postal" class="pure-checkbox">
                    <input name="postal" id="postal" value="postal" type="checkbox" 
                    <?php 
                    if( (strpos($req->type, "postal") != FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Postal
                </label>
                <label for="asile" class="pure-checkbox">
                    <input name="asile" id="asile" value="asile" type="checkbox"
                    <?php 
                    if( (strpos($req->type, "asile") != FALSE) ) {
                       echo "checked";
                    }
                    ?>                    
                    > Asile colis
                </label>
                <label for="email" class="pure-checkbox">
                    <input name="email" id="email" value="email" type="checkbox"
                    <?php 
                    if( (strpos($req->type, "email") != FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Email
                </label>
        </div>
<legend>Pays</legend>
        
        <div class="pure-control-group pure-input-1">
                <label for="france" class="pure-checkbox pure-input-1-6">
                    <input id="france" name="france" value="france" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "france") !== FALSE) ) {
                       echo "checked";
                    }
                    ?>                    
                > France
                </label>
                <label for="uk" class="pure-checkbox pure-input-1-6">
                    <input id="uk" name="uk" value="uk" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "uk") !== FALSE) ) {
                       echo "checked";
                    }
                    ?> 
                    > Uk
                </label>
                <label for="germany" class="pure-checkbox pure-input-1-6">
                    <input id="germany" name="germany" value="germany" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "germany") !== FALSE) ) {
                       echo "checked";
                    }
                    ?> 
                    > Germany
                </label>
                <label for="usa" class="pure-checkbox pure-input-1-6">
                    <input id="usa" name="usa" value="usa" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "usa") !== FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Usa
                </label>
                <label for="australia" class="pure-checkbox pure-input-1-6">
                    <input id="australia" name="australia" value="australia" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "australia") !== FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Australia
                </label>
                <label for="canada" class="pure-checkbox pure-input-1-6">
                    <input id="canada" name="canada" value="canada" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "canada") !== FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Canada
                </label>
                <label for="japan" class="pure-checkbox pure-input-1-6">
                    <input id="japan" name="japan" value="japan" type="checkbox"
                    <?php 
                    if( (strpos($req->flags, "japan") !== FALSE) ) {
                       echo "checked";
                    }
                    ?>
                    > Japan
                </label>
        </div>
        <br>
        <legend>Données</legend>
                <div class="pure-control-group">
                    <label>Thematique</label>
                    <select class="pure-input-2-4" id="thematique" name="thematique">
            <?php
              
              $f = "./_theme.txt";
          
              if (file_exists($f)){
                $size = filesize($f);  // File size (how much data to read)
                $fH = fopen($f,"r");   // File handle
                $themelist = fread($fH,$size);
                fclose($fH);
              }
            
              $lines = explode(";", $themelist);
              
              foreach ($lines as $line) {
                 echo '<option value="'.$line.'">'.$line.'</option>'; 
              }
            
            ?>
                    </select>
                </div>
                <div class="pure-control-group pure-input-1-2">
                    <label class="pure-input-1-2">Age</label>
                    <input value="<?php echo trim($req->age); ?>" id="age" name="age" type="text" class="pure-input-1-2" placeholder="Age" required>
                </div>
        
</div>
<br>

<div class="pure-control-group pure-g">
        <label class="pure-u-2-3" for="datapostal">Description ( text )</label>
        <textarea id="datadesc" name="datadesc" class="pure-u-2-3 " type="area" required> 
        <?php echo trim($req->descript); ?>
        </textarea>
        <div class="pure-u-1-3">
         * Enter Description of datacard here ( Left side )<br>
        </div>


        <label class="pure-u-2-3" for="datadigital">Information ( text )</label>
        <textarea id="datainfo" name="datainfo" class="pure-u-2-3 " type="area" required>
        <?php echo trim($req->info); ?>
        </textarea>
        <div class="pure-u-1-3">
         * Enter Additional infos ( Right side )
        </div>
        
</div>

<div class="pure-g">
 <br>

 <div class="pure-control-group pure-u-1-2">
        <br><legend>Postal</legend>
                         
        <label class="pure-input-1-3" for="postalbaserate">Prix Base/Base Rate</label>
        <input value="<?php echo trim($ratepostal[0]) ?>" id="postalbaserate" name="postalbaserate" type="text" class="pure-input-1-3" placeholder="PRIX BASE/BASE RATE" >
        <label class="pure-input-1-3" for="postaldeliveryfee">Frais Livraison / Delivery Fee</label>
        <input value="<?php echo trim($ratepostal[1]) ?>" id="postaldeliveryfee" name="postaldeliveryfee" type="text" class="pure-input-1-3" placeholder="FRAIS LIVRAISON / DELIVERY FEE" >
        <label class="pure-input-1-3" for="postalselectionfee">Frais Sélection / Selection Fee</label>
        <input value="<?php echo trim($ratepostal[2]) ?>" id="postalselectionfee" name="postalselectionfee" type="text" class="pure-input-1-3" placeholder="FRAIS SÉLECTION / SELECTION FEE" >

</div>
 <div class="pure-control-group pure-u-1-2">
        <br><legend>Asile Colis</legend>
        <label class="pure-input-1-3" for="colisbaserate">Prix Base/Base Rate</label>
        <input value="<?php echo trim($ratecolis[0]) ?>" id="colisbaserate" name="colisbaserate" type="text" class="pure-input-1-3" placeholder="PRIX BASE/BASE RATE" >
        <label class="pure-input-1-3" for="colisdeliveryfee">Frais Livraison / Delivery Fee</label>
        <input value="<?php echo trim($ratecolis[1]) ?>" id="colisdeliveryfee" name="colisdeliveryfee" type="text" class="pure-input-1-3" placeholder="FRAIS LIVRAISON / DELIVERY FEE" >
        <label class="pure-input-1-3" for="colisselectionfee">Frais Sélection / Selection Fee</label>
        <input value="<?php echo trim($ratecolis[2]) ?>" id="colisselectionfee" name="colisselectionfee" type="text" class="pure-input-1-3" placeholder="FRAIS SÉLECTION / SELECTION FEE" >

</div>
 <div class="pure-control-group pure-u-1-2">
        <br><legend>Email</legend>
        <label class="pure-input-1-3" for="emailbaserate">Prix Base/Base Rate</label>
        <input value="<?php echo trim($ratemail[0]) ?>" id="emailbaserate" name="emailbaserate" type="text" class="pure-input-1-3" placeholder="PRIX BASE/BASE RATE" >
        <label class="pure-input-1-3" for="emaildeliveryfee">Frais Livraison / Delivery Fee</label>
        <input value="<?php echo trim($ratemail[1]) ?>" id="emaildeliveryfee" name="emaildeliveryfee" type="text" class="pure-input-1-3" placeholder="FRAIS LIVRAISON / DELIVERY FEE" >
        <label class="pure-input-1-3" for="emailselectionfee">Frais Sélection / Selection Fee</label>
        <input value="<?php echo trim($ratemail[2]) ?>" id="emailselectionfee" name="emailselectionfee" type="text" class="pure-input-1-3" placeholder="FRAIS SÉLECTION / SELECTION FEE" >

</div>
</div>

<br>
<div class="pure-control-group">
  <button class="pure-button button-warning button-xlarge" id="upload-btn"><i class="fa fa-upload"></i> Upload Logo</button>
  <button class="pure-button button-success button-xlarge" id="clear-btn">Clear image</button>
  <div class="pure-input-1-3" id="infobox">
  <?php
   if( !empty($req->logo) ){
    echo '<img width="100" height="50" src="./_uploads/'.$req->logo.'">';
   } 
  ?>
  </div>  
  
  <input id="filebox" value="<?php echo trim($req->logo); ?>" name="file" type="text" class="pure-input-2-3" placeholder="filename">

  <div id="progress">
  </div>
</div>
<br>
<div class="pure-control-group pure-u-2-3">
<table id="table_id" class="display">
    <thead>
        <tr>
            <th>id</th>
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
<p>
 <!-- Json:<?php //echo trim($req->countryfile); ?> -->
</p>

<!--
 <div class="pure-control-group">
        <label for="state">State</label>
        <select id="state">
            <option>AL</option>
            <option>CA</option>
            <option>IL</option>
        </select>
</div>


<div class="pure-control-group">
        <label for="remember" class="pure-checkbox">
            <input id="remember" type="checkbox"> Remember me
        </label>
</div>
-->
<br>

        <button type="submit" id="submitbutton" class="pure-button pure-button-primary button-xlarge"> <i class="fa fa-floppy-o"></i> Update Datacard</button>
        <button id="viewbutton" class="pure-button button-success button-xlarge"> <i class="fa fa-eye"></i> View Datacard</button>
    </fieldset>
</form>


<script>
$( document ).ready(function() {

      $('#datadesc').summernote({
        height: 200,
        toolbar: [
        //[groupname, [button list]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['hr']],
          ['codeview', ['codeview']],
          ['fullscreen', ['fullscreen']],
        ]
      });
      $('#datainfo').summernote({
        height: 200,
        toolbar: [
        //[groupname, [button list]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['hr']],
          ['codeview', ['codeview']],
          ['fullscreen', ['fullscreen']],
        ]
      });
      


      $('#clear-btn').on('click', function (e) {
          e.preventDefault();
          $('#filebox').val("blank.png") ;
      });

      $('#viewbutton').on('click', function (e) {
          e.preventDefault();
          location.href = "../datacard/?id=<?php echo $id; ?>";
      });
      
});
</script>
<?php


include( ABSPATH . 'wp-admin/admin-footer.php' );    
    
?>