<?php

/**
 * Connect DB, return PDO. 
 *
 * @param boolean $do_global, set pdo object as global var
 * @param String|null $db_host
 * @param String|null $db_user
 * @param String|null $db_pass
 * @param String|null $db_name
 * @return void
 */
function connect_db(bool $do_global=true,String $db_host=null,String $db_user=null,String $db_pass=null,String$db_name=null){
    if ($do_global) global $bdd;
	
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

/**
 * Creation SQL string for insertion from Array
 * $array[$key]=>$val become "($key1,$key2,...) VALUES ($val1,$val2,...)"
 * 
 * eg : $bdd->query("INSERT INTO table ".arraytomysqlinsert($array).");
 * !! BE CAREFUL : All data need to be cleaned !!
 * @param mixed $array
 * @return string
 */
function arraytomysqlinsert($array){

	$columns = [];
	$data = [];	
	foreach ( $array as $key => $value) {
		$columns[] = $key;
		if ($value!==null)
			$data[] = "'" . $value . "'";
		else
			$data[] = "NULL";
		
	}
	$cols = implode(",",$columns);
	$values = implode(",",$data);
	return "($cols) VALUES ($values)";

}


/**
 * Creation SQL string for update from Array
 * $array[$keys]=>$vals become "$key1='$val1',$key2='$val2,...'"
 * 
 * eg : $bdd->query("UPDATE table SET ".arraytomysqlupdate($array)." WHERE ...");
 * !! BE CAREFUL : All data need to be cleaned !!
 * @param mixed $array
 * @return string
 */
function arraytomysqlupdate($array){
	$i=0;
	$req="";
	foreach ( $array as $key => $value) {
		if ($i!=0) $req.=', ';
		else $i++;
		if ($value===null){
			$req.= $key."=NULL";
		}else{
			$req.= $key."='".$value."'";
		}
	}
	return  $req;
}



/**
 * Return microtime as float value
 * @return float
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Dev function shortcut to print array in html
 * @param mixed $a
 * @return void
 */
function p($a){ echo '<pre>'.print_r($a,true).'</pre>';}

/**
 * Summary of AT
 * Return atan between 0 and 2*pi handling 
 *      - error due to x=0
 *      - y and x negatives values
 * @param mixed $x
 * @param mixed $y
 * @return float
 */
function AT($x,$y){
	if ($x==0 and $y<0){
		$a=-M_PI/2;
	}else if ($x==0 and $y>=0){
		$a=M_PI;
	}else
		$a=atan($y/$x);
	if ($x<0)
		$a+=M_PI;
	if ($a<0)
		$a+=2*M_PI;
	return $a;
	
}

/**
 * Summary of gauss
 * // returns random number with normal distribution: mean=0 std dev=1
 * @return float
 */
function gauss(){   // N(0,1)
    // returns random number with normal distribution:
    //   mean=0
    //   std dev=1
   
    // auxilary vars
    $x=0;
	while($x==0)
		$x=random_0_1();
	
    $y=random_0_1();
   
    // two independent variables with normal distribution N(0,1)
    $u=sqrt(-2*log($x))*cos(2*pi()*$y);
    // $v=sqrt(-2*log($x))*sin(2*pi()*$y);
   
    // i will return only one, couse only one needed
    return $u;
}
/**
 * Summary of gauss_ms
 * returns random number with normal distribution: mean=m std_dev=s
 * @param mixed $m
 * @param mixed $s
 * @return float|int
 */
function gauss_ms($m,$s){   // N(m,s)
    return gauss()*$s+$m;
}

/**
 * returns random number with flat distribution from 0 to 1
 * @return float
 */
function random_0_1(){   // auxiliary function
    // returns random number with flat distribution from 0 to 1
    return (float)rand()/(float)getrandmax();
}

/**
 * returns random number with flat distribution from a to b
 * @return float
 */
function random_a_b($a,$b){
	$r=intval($a+($b+1-$a)*random_0_1());
	if ($r==(1+$b)) return $b;
	else return $r;
}


/*
MYSQL 5 retrocompatibility 
PDO $bdd needs to be used as Global var
*/
function mysql_num_rows($rep){
	return $rep->rowCount();
}

function mysql_query($bdd,$req){
	global $bdd;
	// echo $req.'<br/>';
	$r=$bdd->query($req) or die(print_r($bdd->errorInfo()));
	return $r;
}

function mysql_fetch_assoc($r){
	$r->setFetchMode(PDO::FETCH_ASSOC);
	return $r->fetch();
}
function mysql_fetch_array($r){
	return mysql_fetch_assoc($r)
}
function mysql_close(){
	global $bdd;
	$bdd=null;
}
function mysql_insert_id(){
	global $bdd;
	return $bdd->lastInsertId();
}


?>