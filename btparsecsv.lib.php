<?php

class btparseCSV {

  public function csv_to_array($filename='', $delimiter=';')
  {
      if(!file_exists($filename) || !is_readable($filename))
          return FALSE;
  
      $header = NULL;
      $data = array();
      if (($handle = fopen($filename, 'r')) !== FALSE)
      {
          while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
          {
              if(!$header)
                  $header = $row;
              else
                  $data[] = array_combine($header, $row);
          }
          fclose($handle);
      }
      return $data;
  }
  
  public function change($array, $selctor, $selectvalue, $changecol, $value)
  {
    for($i=0; $i<count($array); $i++){      
      if($array[$i][$selctor]==$selectvalue){
        $array[$i][$changecol]=$value;
      }
    }
    return $array;
  }
  
  public function save($array, $filename)
  {
    $answer=array();
    if(!is_file($filename.'.locked')){
      copy($filename, $filename.'.locked');
      if(!is_file($filename.'.backup')){
        copy($filename, $filename.'.backup');
      }
      $keys=array_keys($array[0]);
      
      $fp = fopen($filename, 'w');
      fputcsv($fp, $keys, ';', "'");
      foreach ($array as $fields) {
        fputcsv($fp, $fields, ';');
      }  
      fclose($fp);
      unlink($filename.'.locked');
      $answer['status']='1';
      $answer['error']='';
    }
    else{
      $answer['status']='0';
      $answer['error']='File locked';
    }
    return $answer;
  }
    
}

?>
