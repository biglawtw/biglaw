<?php

$GLOBALS['THRIFT_ROOT'] = dirname(__FILE__).'/thrift/src';
require_once( $GLOBALS['THRIFT_ROOT'].'/Thrift.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php' );
error_reporting(0);
//hbase thrift
require_once dirname(__FILE__).'/thrift/Hbase.php';
 
//hive thrift
require_once dirname(__FILE__).'/thrift/ThriftHive.php';

//轉民國，後面參數為分隔符號自訂
function dateTo_c($in_date, $in_txt="")
{

    $ch_date = explode("-", $in_date);
    $ch_date[0] = $ch_date[0]-1911;
    $date = '00.00.00';
    
    
    if ($in_txt=="")
    {
        $date = '000000';
        if ($ch_date[0] > 0 ) $date = $ch_date[0]."".$ch_date[1]."".$ch_date[2];
        
    }
    else
    {
        if ($ch_date[0] > 0 ) $date = $ch_date[0]."$in_txt".$ch_date[1]."$in_txt".$ch_date[2];
    }

    return $date;

}

$action = $_REQUEST['action'];

switch($action){
    case "getContext":
	$key = $_REQUEST['key'];
	//open connection
	$socket = new TSocket( 'localhost', 9090 );
	$transport = new TBufferedTransport( $socket );
	$protocol = new TBinaryProtocol( $transport );
	$client = new HbaseClient( $protocol );
	$transport->open();
	//show all tables
	$tables = $client->getTableNames();
	$context = $client->get('BigLaw' , $key , "M:Context" , array());
	$context = $context[0];
	$type = $client->get('BigLaw' , $key , "M:Type" , array());
	$case = $client->get('BigLaw' , $key , "M:Case" , array());
	$court = $client->get('BigLaw' , $key , "M:Court" , array());
	$date = $client->get('BigLaw' , $key , "M:Date" , array());
	$date = $date ;

	if($_REQUEST['type'] == "json"){
		$result = array( "key" => $key ,  "court" => $court , "type" => $type , "case" => $case , "date" => $date , "context" => $context);
		print_r(json_encode($result));	
	} else{
		print_r($context->value);
	}
	break;
    case "keyword":
    	$keyword = $_REQUEST['keyword'];
    	if($keyword == "") exit();
    	mysql_connect("localhost" , "root" , "123456");
    	mysql_select_db("biglaw");
    	$sql = "select number from cache WHERE keyword = '".$keyword."'";
    	$query = mysql_query($sql);
    	$rows = mysql_num_rows($query);
    	if($rows == 0){
    		$transport = new TSocket("localhost", 10000);
		$protocol = new TBinaryProtocol($transport);
		$client = new ThriftHiveClient($protocol);
		$transport->open();
		//show tables
		//$client->execute('select * from `BigLaw` WHERE `mcase` like '.$keyword.' or mcontext like '.$keyword.' or mcourt like '.$keyword.' or mnumber like '.$keyword.' or mtype like '.$keyword);
		$client->execute('select key from `BigLaw` WHERE `mcontext` like "%'.$keyword.'%"');
		$rows = $client->fetchAll();
		$result = array();
		foreach ($rows as $row){
			$result[] = $row;
			$sql = "insert into `cache`(`keyword`,`number`) values('".$keyword."' , '".$row."') ;";
			mysql_query($sql);
		        /*echo "<fieldset>";
			print_r( str_replace( '\n' , "<br />" ,$row ));
		        echo "<br />";
		        echo "</fieldset>";*/
		}
		echo json_encode($result);
    	}else{
    		$result = array();
    		while($data = mysql_fetch_array($query)){
    			$result[] = $data['number'];
    		}
    		echo json_encode($result);
    	}
    	break;
    case "cleancache":
    	mysql_connect("localhost" , "root" , "123456");
    	mysql_select_db("biglaw");
    	$sql = "TRUNCATE TABLE cache";
    	$query = mysql_query($sql);
    	break;
	case "count":
		$keyword = $_REQUEST['keyword'];
		mysql_connect("localhost" , "root" , "123456");
    	mysql_select_db("biglaw");
    	$sql = "select count(*) from cache WHERE keyword = '".$keyword."'";
    	$query = mysql_query($sql);
		break;
}
