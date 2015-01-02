<?php 
// Script that will creat an xml file for the DB Table.
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'yourdb'; 
$table = 'yourDbTable';
$table_description = 'Db Table Description';

// show error
error_reporting();
ini_set('display_error', 1 );



try{

$db = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $user, $pass );
$query = "SHOW columns FROM {$table}";
$xml = new SimpleXMLElement('<table></table>');
$xml->addAttribute('name', $table );
$xml->addAttribute('description', $table_description );
  
    foreach( $db->query($query) as $row ){
		$child = $xml->addChild('field');
        $child->addAttribute('name', $row['Field']);
        
		if( $row['Key'] =='PRI' ){
			$child->addAttribute('type', 'hidden');
			$xml->addAttribute('primary_key', $row['Field'] );            
        }else{
			$child->addAttribute('type', 'input');
        }
    }
    
}catch (Exception $e ){
    echo $e->getMessage();
}


// get as xml
$content =  $xml->asXML();

// create dom
$dom = new DOMDocument('1.0');
// remove white space
$dom->preserveWhiteSpace = false;
// format the xml
$dom->formatOutput = true;
$dom->loadXML($content);

// content as pure xml.
$content =  $dom->saveXML();

// save into a xml file in the same directory.
file_put_contents($table.'.xml' , $content );

?>