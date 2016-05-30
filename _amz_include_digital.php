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


function exportDigital(){

  $cards = new Datadigital();
  $req = $cards::all();
  
  //$formattedcards = '{"id":"2","name":"Fichiers Digitaux","description":"","author":"1","last_modified":"2014-07-03 07:36:05","';
  $formattedcards = '{"data":'.arToJson($req).'}';
  //$formattedcards .= ',"options":{"last_editor":0,"table_head":false,"table_foot":false,"alternating_row_colors":true,"row_hover":true,"print_name":false,"print_name_position":"above","print_description":false,"print_description_position":"below","extra_css_classes":"","use_datatables":true,"datatables_sort":true,"datatables_filter":true,"datatables_paginate":true,"datatables_lengthchange":true,"datatables_paginate_entries":10,"datatables_info":true,"datatables_scrollx":false,"datatables_custom_commands":""},"visibility":{"rows":[1,1,1],"columns":[1,1,1,1,1,1,1,1,1]}}';
  
  $JSON_folder     = '.';
  $filename 	= "digital_tablepress";
  $JSONFileName    = $JSON_folder.'/'.$filename.'.json';
  $FileHandle     = fopen($JSONFileName, 'w') or die("can't open file");
  fclose($FileHandle);
  $fp = fopen($JSONFileName, 'w');

  fwrite($fp, $formattedcards);
  
  
  fclose($fp); 

}


function exportPostal(){

  $cards = new Datacard();
  $req = $cards::all();
  $formattedcards = '{"data":'.arToJson($req).'}';
  
  $JSON_folder     = '.';
  $filename 	= "datacard_tablepress";
  $JSONFileName    = $JSON_folder.'/'.$filename.'.json';
  $FileHandle     = fopen($JSONFileName, 'w') or die("can't open file");
  fclose($FileHandle);
  $fp = fopen($JSONFileName, 'w');

  fwrite($fp, $formattedcards);
  
  
  fclose($fp); 
  
}

?>

<br>

<table id="table_digital" class="display">

    <thead>
        <tr>
            <th>id</th>
            <th>title</th>
            <th>potentiel</th>
            <th>repartition</th>
            <th>age</th>
            <th>mmc</th>
            <th>thematique</th>
            <th>image</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 2</td>
        </tr>

    </tbody>

</table>

    <button id="resetfilter">Reset Filter</button>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="../_dataTables/media/css/jquery.dataTables.css" />
<link rel="stylesheet" href="../_dataTables/extensions/TableTools/css/dataTables.tableTools.css" />
<script src="../_dataTables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="../_dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>

<script>

      var table;

      function SearchMe(id,str){
      
        table.search(str).draw();
      
      };
      

$( document ).ready(function() {


      $('#resetfilter').click(function(){
      
        table
         .search( '' )
         .columns().search( '' )
         .draw();
      
      });



      table = $('#table_digital').DataTable( {
        "processing": true,
        "serverSide": false,
        //"sAjaxDataProp":"",
        "columns": [
            { "data": "id", "width": "5%", "visible": false },
            { "data": "title", "width": "20%" },
            { "data": "potentiel", "width": "15%"  },
            { "data": "repartition" },
            { "data": "age", "width": "5%"   },
            { "data": "mmc" },
            { "data": "thematique" },
            { "data": "image", "visible":false  },
        ],
        "columnDefs": [
        {
          "targets": 1, // title
          "render": function ( data, type, full, meta ) {
            return '<a href="../displaydigital/?id='+full.id+'"><span style="color:#fff;font-weight: bolder;">'+data+'</span></a>';
          }
        },
        {
          "targets": 3, // repartition
          "render": function ( data, type, full, meta ) {
            var searchstr = 'SearchMe(0,\''+data+'\');';
            return '<a href="#" onClick="'+ searchstr +'"><span style="color:#fff;">'+data+'</span></a>';
          }
        },
        {
          "targets": 2, // potentiel 
          "render": function ( data, type, full, meta ) {
            var searchstr = 'SearchMe(0,\''+data+'\');';
            return '<a href="#" onClick="'+ searchstr +'"><span style="color:#fff;">'+data+'</span></a>';
          }
        },
        {
          "targets": 4, // age
          "render": function ( data, type, full, meta ) {
            var searchstr = 'SearchMe(0,\''+data+'\');';
            return '<a href="#" onClick="'+ searchstr +'"><span style="color:#fff;">'+data+'</span></a>';
          }
        },
        {
          "targets": 5, // mmc
          "render": function ( data, type, full, meta ) {
            var searchstr = 'SearchMe(0,\''+data+'\');';
            return '<a href="#" onClick="'+ searchstr +'"><span style="color:#fff;">'+data+'</span></a>';
          }
        },
        {
          "targets":6, // thematique
          "render": function ( data, type, full, meta ) {
            var searchstr = 'SearchMe(0,\''+data+'\');';
            return '<a href="#" onClick="'+ searchstr +'"><span style="color:#fff;">'+data+'</span></a>';
          }
        },
        {
          "targets": 7,
          "data": "image",
          "render": function ( data, type, full, meta ) {
            if(data!=""){
              return '<img src="../wp-admin/_uploads/'+data+'">';
            }else{
              return '';
            }
          }
        } ],
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "aButtons": [
                  {
                      "sExtends": "pdf",
                      "sFileName": "export.pdf",
                      "mColumns": "visible"
                  },
                  /*{
                      "sExtends": "csv",
                      "sFileName": "export.csv"
                  }*/
                ],
            "sSwfPath": "../_dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
        },
        "ajax": "../digital_tablepress.json"
      } );
      

});


</script>



