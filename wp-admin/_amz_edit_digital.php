<?php

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

require './_amz_datas/ActiveRecord.php';
require './_amz_datas/ActiveRecord.model.digital.php';

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
    echo $e->getMessage();
    echo "<p> <a href='_amz_list_digital.php'><<-- Get back to list</a> </p>";
    die();
  }  
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

function submitForm(){

    $('textarea[name="description"]').html($('#description').code());
    //alert( JSON.stringify( table.data().toArray() ));

    var formdata =  $( '#myform' ).serialize() ;    
    console.log (formdata);
    
    var request  = $.ajax({ 
      type: "POST",
      data: formdata, 
      url:"_amz_datas/digital_edit.php"
      //url:"_amz_datas/echo.php"
    });
    
    request.done(function( msg ) {
      switch (msg)
      {
        case 'success': 
          var reqmsg = "Data updated successfully !";
          break;
        default: 
          var reqmsg = "Error Adding data ("+ msg +" )";
          break;
      }
      $.notify(reqmsg,{ position:"bottom center", className:"info"} );
    });
/*    
    request.fail(function( jqXHR, textStatus ) {
      $.notify(textStatus+"( Request fail )",{ position:"bottom right", className:"error"} );
    });
*/

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
          
           

/*        
         vex.dialog.confirm({
          message: 'Are you absolutely sure you want to destroy the alien planet?',
          callback: function(value) {
              if(value==true)
                $('#form').submit();
                
              return value;
          }
        });  
  */      
              
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
                $.notify("Wrong Filetype" ,{ position:"bottom center", className:"error"} ); 
              },
              onSizeError: function(filename, fileSize) {
                $.notify("Size Limit exceeded" ,{ position:"bottom center", className:"error"} ); 
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


      
    });



  </script>
  
  

<form id="myform" action="#" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <h1>AMAZONITE <small>Datacard <b>Digital</b> ( update #<?php echo $id; ?>)</small></h1>

<!-- <button class="pure-button button-success button-xlarge">Francais</button> -->
<!-- <button class="pure-button button-success button-xlarge">English</button> -->

<input value="<?php echo $id; ?>" type="hidden" id="cardid" name="cardid">

<div class="pure-control-group">
        <label for="title">Titre</label>
        <input value="<?php echo trim($req->title); ?>" id="title" name="title" type="text" class="pure-input-1-2" placeholder="Datacard title" required>
</div>
<br>
<div class=" pure-g pure-control-group">
    <div class="pure-u-1-2">
            <label >Potentiel</label>
            <input value="<?php echo trim($req->potentiel); ?>" id="potentiel" name="potentiel" type="text" class="pure-input-2-3" placeholder="potentiel" required>
            <label >Repartition</label>
            <input value="<?php echo trim($req->repartition); ?>" id="repartition" name="repartition" type="text" class="pure-input-2-3" placeholder="repartition" required>
    </div>
    <div class="pure-u-1-2">
            <label class="pure-input-1-2">Age</label>
            <input value="<?php echo trim($req->age); ?>" id="age" name="age" type="text" class="pure-input-1-2" placeholder="Age" required>
              
            <!-- <input id="age" name="age" type="text" class="pure-input-2-3" placeholder="age" required> -->
            <label class="pure-input-1-3">MMC</label>
            <input value="<?php echo trim($req->mmc); ?>" id="mmc" name="mmc" type="text" class="pure-input-2-3" placeholder="mmc" required>
    </div>
    <div class="pure-u-1-2">
            <label class="pure-input-1-3">Origine</label>
            <input value="<?php echo trim($req->origine); ?>" id="origine" name="origine" type="text" class="pure-input-2-3" placeholder="origine" required>
            <label class="pure-input-1-3">Thematique</label>
            <input value="<?php echo trim($req->thematique); ?>" id="thematique" name="thematique" type="text" class="pure-input-2-3" placeholder="thematique" required>
    </div>
        
        <br>
        <label class="pure-u-1" for="description">Description ( text )</label>
        <textarea id="description" name="description" class="pure-u-1-3 " type="area" required> 
        <?php echo trim($req->description); ?>
        </textarea>
        <div class="pure-u-1-3">
         * Enter Description of datacard here ( Left side )<br>
        </div>      
    
</div>

<br>


<br>
<div class="pure-control-group">
  <button class="pure-button button-warning button-xlarge" id="upload-btn"><i class="fa fa-upload"></i> Upload image</button>
  <button class="pure-button button-success button-xlarge" id="clear-btn">Clear image</button>
  <div class="pure-input-1-3" id="infobox">
    <?php
   if( !empty($req->image) ){
    echo '<img width="150" height="100" src="./_uploads/'.$req->image.'">';
   } 
  ?>
  </div>  
  
  <input value="<?php echo trim($req->image); ?>" id="filebox" name="file" type="text" class="pure-input-2-3" placeholder="filename">

  <div id="progress">
  </div>
</div>
<br>


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

        <button type="submit" id="submitbutton" class="pure-button pure-button-primary button-xlarge"><i class="fa fa-floppy-o"></i> Save Datacard</button>
    </fieldset>
</form>


<script>
$( document ).ready(function() {

     
      $('#description').summernote({
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

});
</script>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );    
    
?>