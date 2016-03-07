<?php

class AuditCLI extends CLIHandler {

    public function main(){
        /** @var SpiderModel $model */
        $model = loadModel("spider");

        $unAuditTaskList = $model->getUnAuditTaskList();

        for($i = 0 , $len = count($unAuditTaskList) ; $i < $len ; $i++){
            $getOne = $unAuditTaskList[$i];
            $subtask = $model->getSubTaskByID($getOne['id']);

            foreach($subtask as $key => $value){
                if($value['audit'] == 0 or $value['audit'] == 1){
                    $path = ROOT .  '/public/jupload/origin/' . $getOne['code'] . '/M/' . $value['date'] . '/' ;
                    if($value['total'] == 0){
                        $model->setSubTaskAudit($value['id'] , 2);
                        continue;
                    }
                    $count = count(array_diff(scandir($path) , array('.' , '..')));
                    if($count != $value['total']){
                        $data = array(
                            'SubTaskID' => $value['id'],
                            'Real' => $value['total'],
                            'Total' => $count
                        );
                        $model->insertAudit($data);
                        $model->setSubTaskAudit($value['id'] , 1);
                    }else{
                        $model->setSubTaskAudit($value['id'] , 2);
                    }
                }
            }
        }
        $model->setRunAudit(0);
    }
}