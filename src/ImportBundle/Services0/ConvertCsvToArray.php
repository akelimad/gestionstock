<?php
namespace ImportBundle\Services;

 
class ConvertCsvToArray {
    
    public function __construct()
    {
       
    }
    
    public function convert($filename, $delimiter = ';') 
    {
        if(!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }
        
        $header = NULL;
        $data = array();
        
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if(!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        if($data){
            return $data;
        }else{
            $output->writeln(' cant read from file ');
        }
    }
 
}
