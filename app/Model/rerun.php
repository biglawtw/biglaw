<?php

/** @property lib_mysql $_db */
class RerunModel extends Model {

    function rerunAuditFailSubTask(){

        $sql = <<<GETALLAUDITFAILTASKANDSUBTASK
SELECT Task.id , SubTask.id as subtaskID , Court.`code` , Court.`name` , Type.`code` , Type.`name` , SubTask.date , SubTask.total , SubTask.processing , SubTask.startTime , SubTask.finishtime , SubTask.audit
FROM Task
LEFT JOIN SubTask on Task.id = SubTask.TaskID
LEFT JOIN Court on Court.id = Task.court
LEFT JOIN Type on Type.id = Task.type
WHERE SubTask.audit = 1
GETALLAUDITFAILTASKANDSUBTASK;

        $this->_db->query($sql);
        $list = $this->_db->getDatas();

        foreach($list as $value){
            $path = ROOT . "/public/jupload/origin/" . join("/" , array($value[2] , $value[4] , $value[6]));
            $this->deleteFolder($path);
            $sql = "DELETE FROM Log WHERE SubTaskID=" . $value['subtaskID'];
            $this->_db->query($sql);
            $sql = "UPDATE SubTask SET total = NULL , processing = NULL , startTime = NULL , finishtime = NULL , audit = 0 WHERE id=" . $value['subtaskID'];
            $this->_db->query($sql);
            $sql = "UPDATE Task SET finishTime = NULL WHERE id=" . $value['id'];
            $this->_db->query($sql);
        }

    }

    private function deleteFolder($dir){
        if(is_file($dir)){
            unlink($dir);
        }else if(is_dir($dir)){
            $files = glob($dir . "/*");
            foreach($files as $value){
                $this->deleteFolder($value);
            }
            rmdir($dir);
        }
    }

} 