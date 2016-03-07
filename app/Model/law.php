<?php

class LawModel extends Model {

    function getHasnotlaw(){
	$this->_db->query("SELECT * FROM Log WHERE haslaw = 0 and refereebook is not null LIMIT 0 ,1");
	return $this->_db->getData();
    }

    function getRefereebookPathByLog($id){
	$sql = <<<GETREFEREEBOOKPATH
SELECT `Log`.id as logid , `Log`.`Filename` as fname , `SubTask`.date as stdate , `Type`.`code` as tcode ,  `Court`.code as ccode FROM `Log`
LEFT JOIN `SubTask` on `SubTask`.id = `Log`.`SubTaskID`
LEFT JOIN `Task` on `Task`.id = `SubTask`.`TaskID`
LEFT JOIN Court on Court.id = Task.court
LEFT JOIN Type on Type.id = Task.type
WHERE Log.id = $id
GETREFEREEBOOKPATH;
	$this->_db->query($sql);
	return $this->_db->getData();
    }

    function updatehaslaw($id){
	$this->_db->query("UPDATE Log SET haslaw=1 WHERE id=$id");
    }

    function insert($id , $name , $num){
	$data = array(
	    "LogID" => $id,
	    "lawname" => $name,
	    "lawnum" => $num
	);
	$this->_db->insert('Uselaw' , $data);
    }


}
