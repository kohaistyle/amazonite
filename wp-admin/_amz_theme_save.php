<?php


  $f = '_theme.txt';


  if (file_exists($f)){
    //$size = filesize($f);  // File size (how much data to read)
    $fH = fopen($f,'w+');   // File handle
    
    $cont = $_POST['themelist'];
    
    fwrite($fH, $cont);
    fclose($fH);
    
    echo '1';
    
  }else{
  
     echo '0'; 
  
  }

?>