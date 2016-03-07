<?php

class apiModel extends Model{

    public function getStatisicData($ids){
        $sql = <<<GETSTATISIC
SELECT Location ,  count(*) as count FROM refereebook
WHERE ID in ($ids)
GROUP BY Location
GETSTATISIC;

        $this->_db->query($sql);
        $data1 = $this->_db->getDatas();

        $sql = <<<GETSTATIC2
SELECT SubTask.date ,  count(refereebook.ID) as count FROM refereebook
LEFT JOIN Log on Log.refereebook = refereebook.ID
LEFT JOIN SubTask on Log.SubTaskID = SubTask.id
WHERE refereebook.ID in ($ids)
GROUP BY SubTask.date
GETSTATIC2;

        $this->_db->query($sql);
        $data2 = $this->_db->getDatas();

        $statisic2 = array();

        foreach($data1 as $key => $value){
            $statisic2[] = array(
                $value['Location'] , $value['count']
            );
        }

        $statisic3 = array();

        foreach($data2 as $key => $value){
            $statisic3[] = array(
                $value['date'] , $value['count']
            );
        }

        $result = array(
            'chart2' => $statisic2,
            'chart3' => $statisic3
        );
        return $result;
    }

    public function getCase($id){
        $sql = "SELECT * FROM refereebook WHERE ID=$id";
        $this->_db->query($sql);
        if($this->_db->getNum()>0){
            return $this->_db->getData();
        }
        return 0;
    }

    public function getLaw($id){
        $this->_db->query("SELECT * FROM Uselaw WHERE Uselaw.LogID = (SELECT Log.id FROM Log WHERE Log.refereebook = $id)");
        return $this->_db->getData();
    }

}