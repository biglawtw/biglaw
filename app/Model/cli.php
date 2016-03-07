<?php

/** @property lib_mysql $_db */
class CliModel extends Model {

    function getCount(){
        $this->_db->query("SELECT COUNT(*) as count FROM refereebook");
        return $this->_db->getData()['count'];
    }

    function getRealDate($id){
        $sql = "SELECT SubTask.date FROM Log LEFT JOIN SubTask on SubTask.id = Log.SubTaskID WHERE Log.refereebook = $id";
        $this->_db->query($sql);
        return $this->_db->getData()['date'];
    }

    function updateRefereebookDate($id , $date){
        $sql = "UPDATE refereebook SET Judgedate='$date' WHERE ID=$id";
        $this->_db->query($sql);
    }

} 