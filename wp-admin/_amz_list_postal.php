<?php

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

include '_amz_datas/export2json.php';

exportPostal();

  // Clean our junk .json files
  $mask = "*.json";
  array_map( "unlink", glob( $mask ) );

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


<!-- JQuery.Datatables -->
<link rel="stylesheet" href="_dataTables/media/css/jquery.dataTables.css" />
<link rel="stylesheet" href="_dataTables/extensions/TableTools/css/dataTables.tableTools.css" />
<script src="_dataTables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="_dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>

<!-- Rich Text Editor -->
  <script type="text/javascript">
  
  var table=0;
  
  function ajaxDelete(thisid){
  
      var request  = $.ajax({ 
      type: "GET",
      data: { id : thisid}, 
      url:"_amz_datas/datacard_delete.php" 
    });

        
    request.done(function( msg ) {
    
      var reqmsg = '';
      
      switch (msg)
      {
        case 'success': 
          reqmsg = "Data deleted successfully !";
          break;
        default: 
          reqmsg = "Error deleting data ("+ msg +" )";
          break;
      }
      $.notify(reqmsg,{ position:"bottom center", className:"info"} )
      window.location.reload();
    });
/*
    request.fail(function( jqXHR, textStatus ) {
      $.notify(textStatus+"( Request fail )",{ position:"bottom right", className:"error"} );
    });
*/    
    
  
  }
  
  
  function deleteDatacard(id){
    
    vex.dialog.confirm({
      message: 'Confirm deletion of Datacard Postal #'+id+' ?',
      callback: function(value) {
          if(value==true)
            //$('#form').submit();
            ajaxDelete(id);
            
          //return value;
      }
    });   
  
  } 
  
    $(function() {
    
      $('.summernote').summernote({
        height: 200,
        toolbar: [
        //[groupname, [button list]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['codeview', ['codeview']],
          ['fullscreen', ['fullscreen']],
        ]
      });




      $.fn.dataTable.TableTools.defaults.aButtons = [ "pdf", "csv"];

      table = $('#table_postal').DataTable( {
        "processing": true,
        "serverSide": false,
        //"sAjaxDataProp":"",
        "columns": [
            { "data": "id", "width": "5%" },
            { "data": "title" },
            { "data": "type" },
            { "data": "flags" },
            { "data": "thematique" },
            { "data": "age" }
        ],
        "columnDefs": [ {
          "targets": 6,
          "data": "id",
          "render": function ( data, type, full, meta ) {
            return '<a href="_amz_edit_postal.php?id='+data+'">Edit</a> - <a href="#" onClick="deleteDatacard('+data+');">Delete</a>';
          }
        } ],
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "aButtons": [
                  {
                      "sExtends": "pdf",
                      "sFileName": "export.pdf"
                  },
                  {
                      "sExtends": "csv",
                      "sFileName": "export.csv"
                  }
                ],
            "sSwfPath": "_dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
        },
        "ajax": "_amz_datas/datacard.json"
      } );

    
    

      $('#theme-btn').on('click', function (e) {
        //alert($('.summernote').code());
        e.preventDefault();
        
        //alert( $('#themelist').val() );
        
        $.ajax({
      			type: "POST",
      			url: "_amz_theme_save.php",
      			data: 'themelist='+$('#themelist').val(),
            success: function() {
      				  $.notify("Themes saved !",{ position:"bottom center", className:"info"} )
         		}
			});
        
        
        
      });
    });
  </script>
  

<h1>AMAZONITE <small>Manager <b>Postal</b> ( list )</small></h1>  

  
<table id="table_postal" class="display">
    <thead>
        <tr>
            <th>id</th>
            <th>title</th>
            <th>type</th>
            <th>country</th>
            <th>thematique</th>
            <th>age</th>
            <th>actions</th>
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

<h1> Themes ( edit list ): </h1>

<?php

  $f = "./_theme.txt";


  if (file_exists($f)){
    $size = filesize($f);  // File size (how much data to read)
    $fH = fopen($f,"r");   // File handle
    $themelist = fread($fH,$size);
    fclose($fH);
  }
  
?>

<input value="<?php echo $themelist; ?>" style="width:400px" id="themelist" name="themelist" type="text" placeholder="themes">

<button class="pure-button button-success button-xlarge" id="theme-btn">Save Theme List</button>

<script>
$( document ).ready(function() {


});
</script>
