<?php

class SplitterCLI extends CLIHandler {

    public function main()
    {
        /** @var SpiderModel $model */
        $model = loadModel("spider");
        echo "Start Splitter CLI \r\n";
        while (true) {
            Log::write("Load Un Splitter SubTask", 1, "splitter");
            $isRunSplitter = $model->runSplitter()['key'];
            if ($isRunSplitter == "1") {
                $data = $model->getUnSplitterSubTask();
                $splitter = $model->getSplitter()['filename'];
                if ($data > 0) {
                    Log::write("Load SubTask: " . $data['id'], 1, "splitter");
                    $logs = $model->getUNSplitterLog($data['id']);
                    foreach ($logs as $key => $value) {
                        if (file_exists(ROOT . "/public/jupload/origin/" . $value['CourtCode'] . "/" . $value['TypeCode'] . "/" . $value['date'] . "/" . $value['Filename'] . ".txt")) {
                            Log::write("Splitter: " . ROOT . "/public/jupload/origin/" . $value['CourtCode'] . "/" . $value['TypeCode'] . "/" . $value['date'] . "/" . $value['Filename'] . ".txt", 1, "splitter");
                            exec("python3.4 " . ROOT . "/public/splitter/" . $splitter . " " . ROOT . "/public/jupload/origin/" . $value['CourtCode'] . "/" . $value['TypeCode'] . "/" . $value['date'] . "/" . $value['Filename'] . ".txt" , $output);
                            $pattern = array("\r\n" , "\n" , "\r");
                            //$output[0] = trim(str_replace($pattern , "" , $output[0]));
                            echo $value['id'] . '->' .  $output[0] . "\r\n";
                            $model->updateSpliterLog($value['id'], $output[0]);
                            unset($output);
                        } else {
                            Log::write("Splitter: " . ROOT . "/public/jupload/origin/" . $value['CourtCode'] . "/" . $value['TypeCode'] . "/" . $value['date'] . "/" . $value['Filename'] . ".txt" . "not found.", 1, "splitter");
                            $model->updateSpliterLog($value['id'], "E005");
                        }
                    }
                    Log::write("SubTask: " . $data['id'] . " splitter finished.", 1, "splitter");
                    $model->updateSubTaskSplitter($data['id']);
                } else {
                    sleep(60);
                }
            } else {
                sleep(60);
            }
        }
    }

}