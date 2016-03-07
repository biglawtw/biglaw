<?php

class UpdaterefereebookdateCLI extends CLIHandler {

    public function main(){
        /** @var cliModel $model */
        $model = loadModel("cli");
        $count = $model->getCount();

        echo "Start Update \r\n";
        for($i = 1 ; $i <= $count ; $i++){
            $date = $model->getRealDate($i);
            $model->updateRefereebookDate($i , $date);
            echo  "[" . ceil(($i/$count)*100) . "% $i/$count] Update Refereebook:$i , Set date = $date \r\n";
        }
    }
}