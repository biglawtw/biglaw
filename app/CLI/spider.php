<?php

class SpiderCLI extends CLIHandler
{

    public function main()
    {
        ini_set("max_execution_time", "0");
        $mindealy = 0;
        $maxdelay = 30;
        $delay = 0;
        echo "Spider Process CLI Start\r\n";
        Log::write("Spider Process CLI Start", 1, "spider");
        /** @var spiderModel $model */
        $model = loadModel("spider");
        while (true) {
            Log::write("Load Un Finished Task", 1, "spider");
            // Process SubTask
            $data = $model->getUnFinishedList();
            if (count($data) > 0) {
                // fetch a subtask to do
                $task = $data[0];
                Log::write("Fetch SubTaskID:" . $task['SubTaskID'], 1, "spider");
                $processing = $task['processing'];
                // get total
                if (empty($task['total'])) {
                    Log::write("Query Total of SubTaskID:" . $task['SubTaskID'], 1, "spider");
                    $total = $this->gettotal($task);
                    if ($total == -1) {
                        Log::write("Get Total Data Error: SubTaskID:" . $task['SubTask'], 3, "spider");
                        sleep($delay);
                        $delay++;
                        if ($delay > $maxdelay) $delay = $maxdelay;
                        $this->detectServerRepair();
                        continue;
                    }
                    Log::write("Total of SubTaskID:" . $task['SubTaskID'] . " is " . $total, 1, "spider");
                    $model->updateSubTaskTotal($task['SubTaskID'], $total);
                    // delay for curl
                    sleep($delay);
                    $delay--;
                    if ($delay < $mindealy) $delay = $mindealy;
                    $processing = 1;
                } else {
                    $total = $task['total'];
                }
                $model->startSubTask($task['SubTaskID']);
                while ($processing <= $total) {
                    echo "SubTask ID:" . $task['SubTaskID'] . " Total:" . $total . ",processing:" . $processing . "\r\n";
                    Log::write("Processing SubTaskID:" . $task['SubTaskID'] . "," . $task["CourtName"] . "," . $task["SYSName"] . "   " . $processing . "/" . $total, 1, "spider");
                    $model->updateSubTaskProcessing($task['SubTaskID'], $processing);
                    $state = $this->fetchOneData($task, $processing);
                    if ($state == 0) {
                        // 正常
                        $processing++;
                        sleep($delay);
                        $delay--;
                        if ($delay < $mindealy) $delay = $mindealy;
                    } else {
                        // 異常
                        Log::write("Fetch: " . $task['SubTask'] . "/" . $processing . " failed", 3, "spider");
                        $delay++;
                        if ($delay > $maxdelay) $delay = $maxdelay;
                        sleep($delay);
                        $this->detectServerRepair();
                    }
                }
                $model->finishSubTask($task['SubTaskID']);
            } else {
                sleep(5);
            }
            $this->detectServerRepair();
            $this->processTask();
        }
    }

    private function processTask()
    {
        /** @var spiderModel $model */
        $model = loadModel("spider");
        $state = $model->gettaskstate();
        print_r($state);
        for ($i = 0; $i < count($state); $i++) {
            $turple = $state[$i];
            if ($turple['unfinishCount'] == 0) {
                $model->updateTaskToFinish($turple['id']);
                Log::write("Task " . $turple['id'] . " finished at " . date("Y-m-d H:i:s", time()), 1, "spider");
            }
        }
    }

    private function detectServerRepair()
    {
        // 03:00 ~ 04:00 Server Repair , so delay 1 hour
        $h = date("H", time());
        if ($h == "03") {
            Log::write("Server Repair Time,", 2, "spider");
            sleep(3600);
        }
    }

    private function fetchOneData($data, $id)
    {
        /** @var spiderModel $model */
        $model = loadModel("spider");
        $fjudQueryURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY02_1.aspx'; //案件列表
        $fjudContextURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx'; //案件內容
        $postdata = array(
            "nccharset" => "F0BEF8A4", //亂數 Hidden 欄位
            'v_court' => $data["CourtName"], //法院名稱
            'v_sys' => $data["SYSCode"], //裁判類別
            'jud_year' => '', // 判決字號 年
            //'sel_judword' => "常用字別", //判決字號 字別下拉式選單
            "jud_case" => "", //判決字號 字別
            "jud_no" => "", //判決字號 第 ? 號
            "jt" => "", //判決案由
            //"dy1" => "103",
            //"dm1" => "5",
            //"dd1" => "1",
            //"dy2" => "103",
            //"dm2" => "5",
            //"dd2" => "1",
            //"kw" => "", //全文檢索語詞
            "keyword" => "", //hidden
            "sdate" => date("Ymd", strtotime($data["date"])), // hidden
            "edate" => date("Ymd", strtotime($data["date"])), // hidden
            "jud_title" => "", // hidden
            //"Button" => "查詢", //what the fuck...
            "searchkw" => "", // hidden
            "id" => $id,
            "page" => ""
        );
        try {
            $result = $this->requestURI($fjudContextURL, 'GET', $postdata, $fjudQueryURL, "http://jirs.judicial.gov.tw", true);
            $content_regex = "/<pre>([\d\D]*)<\/pre>/";
            preg_match_all($content_regex, $result, $contentmatch);
            $content = $contentmatch[1][0];

            preg_match_all('/\[.*\\\\(.*\..{3})\]/', $result, $filenamematch);
            $filename = $filenamematch[1][0];
            $html = str_get_html($result);

            $contents = array(
                '標題' => $html->find('title', 0)->plaintext,
                '查詢類別' => $html->find('b', 0)->plaintext,
                '【裁判字號】' => $html->find('span', 4)->plaintext,
                '【裁判日期】' => $html->find('span', 6)->plaintext,
                '【裁判案由】' => $html->find('span', 8)->plaintext,
                '【裁判全文】' => $content,
            );
            if (empty($filename) or $filename == "") {
                throw new Exception("error");
            }
            echo $filename . "\r\n";
        } catch (Exception $e) {
            print_r($postdata);
            print_r($e);
            echo $result;
            $html = null;
            Log::write($e->getMessage() . "\r\n" . $e->getTrace(), 3, "spider");
            return 1;
        } finally {
            $html = null;
        }
        // Save origin file

        $filepath = ROOT . "/public/jupload/origin/" . $data["CourtCode"] . "/" . $data["SYSCode"] . "/" . $data["date"] . "/";
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $fileid = 0;

        while (true) {
            if ($fileid == 0) {
                if (file_exists($filepath . $filename . ".txt")) {
                    Log::write("File Exists:" . $filepath . $filename . ".txt", 2, "spider");
                    $fileid++;
                } else {
                    break;
                }
            } else {
                if (file_exists($filepath . $filename . "." . $fileid . ".txt")) {
                    Log::write("File Exists:" . $filepath . $filename . "." . $fileid . ".txt", 2, "spider");
                    $fileid++;
                } else {
                    break;
                }
            }
        }
        if($fileid>0){
            $f = fopen($filepath . $filename . "." . $fileid . ".txt", 'w+');
            $model->insertlog($data['SubTaskID'], $filename . "." . $fileid );
        }else{
            $f = fopen($filepath . $filename . ".txt", 'w+');
            $model->insertlog($data['SubTaskID'], $filename);
        }

        foreach ($contents as $key => $value) {
            fwrite($f, $key . PHP_EOL);
            fwrite($f, $value . PHP_EOL);
        }
        fclose($f);
        return 0;
    }

    private function gettotal($data)
    {
        $fjudEnterURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY01_1.aspx'; //查詢頁面
        $fjudQueryURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY02_1.aspx'; //案件列表
        $postdata = array(
            "nccharset" => "F1BEF8A4", //亂數 Hidden 欄位
            'v_court' => $data["CourtName"], //法院名稱
            'v_sys' => $data["SYSCode"], //裁判類別
            'jud_year' => '', // 判決字號 年
            'sel_judword' => "常用字別", //判決字號 字別下拉式選單
            "jud_case" => "", //判決字號 字別
            "jud_no" => "", //判決字號 第 ? 號
            "jt" => "", //判決案由
            //"dy1" => "103",
            //"dm1" => "5",
            //"dd1" => "1",
            //"dy2" => "103",
            //"dm2" => "5",
            //"dd2" => "1",
            "kw" => "", //全文檢索語詞
            "keyword" => "", //hidden
            "sdate" => date("Ymd", strtotime($data["date"])), // hidden
            "edate" => date("Ymd", strtotime($data["date"])), // hidden
            "jud_title" => "", // hidden
            "Button" => "查詢", //what the fuck...
            "searchkw" => "", // hidden
            "id" => "1",
            "page" => ""
        );
        $result = $this->requestURI($fjudQueryURL, 'POST', $postdata, $fjudEnterURL, "http://jirs.judicial.gov.tw", true);
        $regex = "/共\s+([0-9]+)\s+筆/";
        preg_match_all($regex, $result, $output);
        $total = $output[1][0];

        if ($total == "200") {
            $regex2 = '/共(\d+)筆/';
            preg_match_all($regex2, $result, $out2);
            if (count($out2[1]) > 0) {
                $total = $out2[1][0];
            }
        }

        echo date("Ymd", strtotime($data['date'])) . " - " . $total . "\r\n";
        if ($total == "") {
            $total = -1;
            print_r($output);
            print_r($postdata);
            echo $result;
        }
        return $total;
    }

    public function addtask()
    {
        /** @var spiderModel $model */
        $model = loadModel("spider");

        $model->addNewTask(mktime(0, 0, 0, 5, 1, 2014), mktime(0, 0, 0, 5, 31, 2014), 10, 2);
    }

    public function rebuildlog()
    {
        /** @var spiderModel $model */
        $model = loadModel("spider");
        $dir = ROOT . "/public/jupload/origin/";
        $files = scandir($dir);
        $date = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        for ($i = 1; $i <= 31; $i++) {
            $filepattern = "SLDM1035" . $date[$i];
            echo $filepattern . "\r\n";
            for ($j = 0; $j < count($files); $j++) {
                $s = substr($files[$j], 0, strlen($filepattern));
                if ($s == $filepattern) {
                    $f = substr($files[$j], 0, -4);
                    $model->insertlog($i, $f);
                } else {
                    continue;
                }
            }

        }
    }

    public function listunfinish()
    {
        /** @var spiderModel $model */
        $model = loadModel('spider');
        $result = $model->getUnFinishedList();
        print_r($result);
    }

    /**
     *
     *
     * nccharset:F1BFD785
     * v_court:TPD 臺灣臺北地方法院
     * v_sys:M
     * jud_year:
     * sel_judword:常用字別
     * jud_case:
     * jud_no:
     * jt:
     * dy1:103
     * dm1:5
     * dd1:1
     * dy2:103
     * dm2:5
     * dd2:1
     * kw:
     * keyword:
     * sdate:20140501
     * edate:20140501
     * jud_title:
     * Button: 查詢
     * searchkw:
     */
    public function test()
    {
        $fjudEnterURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY01_1.aspx'; //查詢頁面
        $fjudQueryURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY02_1.aspx'; //案件列表
        $fjudContextURL = 'http://jirs.judicial.gov.tw/FJUD/FJUDQRY03_1.aspx'; //案件內容
        $data = array(
            //"nccharset" => "F1BEF8A4", //亂數 Hidden 欄位
            'v_court' => "TPD 臺灣臺北地方法院", //法院名稱
            'v_sys' => "M", //裁判類別
            'jud_year' => '', // 判決字號 年
            //'sel_judword' => "常用字別", //判決字號 字別下拉式選單
            "jud_case" => "", //判決字號 字別
            "jud_no" => "", //判決字號 第 ? 號
            "jt" => "", //判決案由
            //"dy1" => "103",
            //"dm1" => "5",
            //"dd1" => "1",
            //"dy2" => "103",
            //"dm2" => "5",
            //"dd2" => "1",
            //"kw" => "", //全文檢索語詞
            "keyword" => "", //hidden
            "sdate" => "20140523", // hidden
            "edate" => "20140523", // hidden
            "jud_title" => "", // hidden
            //"Button" => "查詢", //what the fuck...
            "searchkw" => "", // hidden
            "id" => "3",
            "page" => ""
        );
        //$result = $this->requestURI($fjudQueryURL, 'POST', $data, $fjudEnterURL, "http://jirs.judicial.gov.tw");
        $result = $this->requestURI($fjudContextURL, 'GET', $data, $fjudQueryURL, "http://jirs.judicial.gov.tw", true);
        echo $result;
    }

    public function getCourt()
    {
        $model = loadModel("spider");
        $data = $model->getCourt();
        return $data;
    }

    /**
     * 使用curl 發送一個http請求，並取回資料
     * @param String $url : 請求網址
     * @param String $vers : HTTP動詞 GET or POST
     * @param Array $queryString : 查詢參數或是POST Data
     * @param String $referer : 來源參考網址
     * @param String $origin : 來源網域
     * @return String 網頁html內容
     */
    public function requestURI($url, $vers = 'GET', $queryString = null, $referer = null, $origin = null, $proxy = false)
    {
        $ch = curl_init();
        $qs = http_build_query($queryString, "GET");
        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, "http://192.168.188.100:8123");
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        switch ($vers) {
            case "GET":
                if (empty($queryString)) {
                    curl_setopt($ch, CURLOPT_URL, $url);
                } else {
                    curl_setopt($ch, CURLOPT_URL, $url . "?" . $qs);
                }
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $qs);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: ' . $origin
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36');
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        $result = curl_exec($ch);
        if ($result == "") {
            throw new Exception("Proxy Error");
        }
        curl_close($ch);
        return $result;
    }

    public function insertCourt()
    {
        $model = loadModel("spider");
        $M_v_court_list_code = array();
        $M_v_court_list = array(
            0 => 'TPC 司法院－刑事補償',
            1 => 'TPS 最高法院',
            2 => 'TPH 臺灣高等法院',
            3 => 'IPC 智慧財產法院',
            4 => 'TCH 臺灣高等法院 臺中分院',
            5 => 'TNH 臺灣高等法院 臺南分院',
            6 => 'KSH 臺灣高等法院 高雄分院',
            7 => 'HLH 臺灣高等法院 花蓮分院',
            8 => 'TPD 臺灣臺北地方法院',
            9 => 'SLD 臺灣士林地方法院',
            10 => 'PCD 臺灣新北地方法院',
            11 => 'ILD 臺灣宜蘭地方法院',
            12 => 'KLD 臺灣基隆地方法院',
            13 => 'TYD 臺灣桃園地方法院',
            14 => 'SCD 臺灣新竹地方法院',
            15 => 'MLD 臺灣苗栗地方法院',
            16 => 'TCD 臺灣臺中地方法院',
            17 => 'CHD 臺灣彰化地方法院',
            18 => 'NTD 臺灣南投地方法院',
            19 => 'ULD 臺灣雲林地方法院',
            20 => 'CYD 臺灣嘉義地方法院',
            21 => 'TND 臺灣臺南地方法院',
            22 => 'KSD 臺灣高雄地方法院',
            23 => 'HLD 臺灣花蓮地方法院',
            24 => 'TTD 臺灣臺東地方法院',
            25 => 'PTD 臺灣屏東地方法院',
            26 => 'PHD 臺灣澎湖地方法院',
            27 => 'KMH 福建高等法院金門分院',
            28 => 'KMD 福建金門地方法院',
            29 => 'LCD 福建連江地方法院'
        );
        for ($i = 0; $i < 30; $i++) {
            $M_v_court_list_code[$i] = substr($M_v_court_list[$i], 0, 3);
        }

        foreach ($M_v_court_list as $key => $value) {
            $model->insertCourt($M_v_court_list_code[$key], $value);
        }
    }

}

?>