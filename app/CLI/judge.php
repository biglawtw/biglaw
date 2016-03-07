<?php

class JudgeCLI extends CLIHandler{


    public function main(){
	$model = loadModel('law');
	while(true){
	    $getone = $model->getHasnotlaw();
	    if(empty($getone) || $getone['id'] == ""){
		break;
	    }
	    echo $getone['id'] . "\r\n";
	    $pathdata = $model->getRefereebookPathByLog($getone['id']);
	    $path = ROOT . "/public/jupload/origin/" . join("/" , array($pathdata['ccode'] , $pathdata['tcode'] , $pathdata['stdate'] , $pathdata['fname'] . ".txt"));
    //	$path = "/var/www/biglaw-lab/public/jupload/origin/TPD/M/2014-05-01/TPDM10351.002.txt";
	    $data = file_get_contents($path);

	    $ch = curl_init();
	    curl_setopt($ch , CURLOPT_URL , "http://localhost:9808/");
	    curl_setopt($ch , CURLOPT_POST , true);
	    curl_setopt($ch , CURLOPT_POSTFIELDS , $data);
	    curl_setopt($ch , CURLOPT_HTTPHEADER , array('Content-Type: text/plain'));
	    curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);
	    $result = curl_exec($ch);
	    $resultdata = json_decode($result , true);
	    curl_close($ch);

	    $model->updatehaslaw($getone['id']);
	    $model->insert($getone['id'] , json_encode($resultdata[0]['lawname']) , json_encode($resultdata[1]['lawnum']));
	}
    }
}

?>
