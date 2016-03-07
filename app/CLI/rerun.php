<?php

class RerunCLI extends CLIHandler {

    public function main(){
        /** @var RerunModel $model */
        $model = loadModel("rerun");
        $model->rerunAuditFailSubTask();
    }

} 