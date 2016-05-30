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
  $FileHandle     = fopen($JSONFileName, 'w+') or die("can't open file");
  fclose($FileHandle);
  $fp = fopen($JSONFileName, 'w+');

  fwrite($fp, $formattedcards);
  
  
  fclose($fp); 
  
}


?>

<table id="table_postal" class="display">
    <thead>
        <tr>
            <th>id</th>
            <th>Titre/Title</th>
            <th>Catégorie/Type</th>
            <th>Pays/Country</th>
            <th>Thematique/Theme</th>
            <th>Classe d'Age/Age Range</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 1</td>
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
      
        table.column(id).search(str).draw();
      
      };



$( document ).ready(function() {


      $('#resetfilter').click(function(){
      
        table
         .search( '' )
         .columns().search( '' )
         .draw();
      
      });


      //$.fn.dataTable.TableTools.defaults.aButtons = [ "pdf", "csv"];

      table = $('#table_postal').DataTable( {
        "processing": true,
        "serverSide": false,
        "rowCallback": function( row, data ) {

          //$('td:eq(4)', row).html( '<b>A</b>' );
        },
        //"sAjaxDataProp":"",
        "columns": [
            { "data": "id", "width": "5%", "visible":false },
            { "data": "title", "width": "15%" },
            { "data": "type" , "width": "20%"},
            { "data": "flags", "width": "26%" },
            { "data": "thematique" },
            { "data": "age" }
        ],
        "columnDefs": [ 
        {
          "targets": 1,
          "data": null,
          "render": function ( data, type, full, meta ) {
            return '<a href="../displaypostal/?id='+full.id+'"><span style="font-weight:bold;color:#fff">'+full.title+'</span></a>';
            }
          },
        {
          "targets": 2,
          "data": "type",
          "render":function ( data, type, full, meta ) {
                
                //alert(data);
                var html='';
                if(data.indexOf('postal')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(2,\'postal\')"><span style="color:#fff">postal</span></a> / ';
                }
                if(data.indexOf('asile')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(2,\'asile\')"><span style="color:#fff">asile colis</span></a> / ';
                }
                if(data.indexOf('email')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(2,\'email\')"><span style="color:#fff">email</span></a>';
                }
              return html;
              }
          },
        {
          "targets": 3,
          "data": function ( data, type, full, meta ) {
            if (type === 'display') {
              return data;
            }
            else if (type === 'filter') {
              return data;
            }
            return data;
          },
          "render":function ( data, type, full, meta ) {
                
                var html='';
                if(data.indexOf('france')!= -1 ){
                  html = '<a href="#" onClick="SearchMe(3,\'fr\')"><img src="../_flags/FR.png"><span style="display:none;color:#fff">fr</span></a> ';
                }
                if(data.indexOf('uk')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'gb\')"><img src="../_flags/GB.png"><span style="display:none;color:#fff">gb</span></a> ';
                }
                if(data.indexOf('germany')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'de\')"><img src="../_flags/DE.png"><span style="display:none;color:#fff">de</span></a> ';
                }
                if(data.indexOf('usa')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'us\')"><img src="../_flags/US.png"><span style="display:none;color:#fff">us</span></a>  ';
                }
                if(data.indexOf('australia')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'au\')"><img src="../_flags/AU.png"><span style="display:none;color:#fff">au</span></a>  ';
                }
                if(data.indexOf('canada')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'ca\')"><img src="../_flags/CA.png"><span style="display:none;color:#fff">ca</span></a>  ';
                }
                if(data.indexOf('japan')!= -1 ){
                  html += '<a href="#" onClick="SearchMe(3,\'jp\')"><img src="../_flags/JP.png"><span style="display:none;color:#fff">jp</span></a>  ';
                }

              return html;
              }
          },
         {
          "targets": 5,
          "data": "id",
          "render": function ( data, type, full, meta ) {
            return '<a href="#" onClick="SearchMe(5,\''+data+'\')"><span style="color:#fff">'+data+'</span></a>';
          }
        },
         {
          "targets": 4,
          "data": "id",
          "render": function ( data, type, full, meta ) {
            return '<a href="#" onClick="SearchMe(4,\''+data+'\')"><span style="color:#fff">'+data+'</span></a>';
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
        "ajax": "../datacard_tablepress.json"
      } );
      
});
    
</script>
      