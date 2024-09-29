<?php

/**
 * Connect Database and return database object
 * @param string $db_host
 * @param string $db_user
 * @param string $db_pass
 * @param string $db_name
 * @return PDO
 */
function connect_db(String $db_host=null,String $db_user=null,String $db_pass=null,String$db_name=null){
	if ($db_host==null)
	    $db_host = 'XXXXXXX';       // Set your standard host (eg : localhost, IP)
    if ($db_user==null)
	    $db_user = 'XXXXXXX';       // Set your default user
	if ($db_pass==null)
        $db_pass = 'XXXXXXX';       // Set your default password
    if ($db_name==null)
        $db_name = "XXXXXXX";       // Set you default database name
	try{
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		);
		$bdd = new PDO('mysql:host='.$db_host.';dbmane='.$db_name.';charset=utf8',$db_user,$db_pass,$options);
		$bdd->query('USE '.$db_name);
        return $bdd;
	}catch(Exception $e){
        die('Error DB connection,  info :'. $e->getMessage());
	}
}


?>