<?php

/**
 * @property lib_request $_request
 * @property SpiderModel $_model
 */
class spiderController extends Controller{

    public function __construct(){
        $this->_opdata['sidebar'] = array(
            "Dashboard" => url("index" , "spider"),
            "Refereebook" => array(
                "以ID查詢" => url("qrbbyid" , "spider"),
                "打包下載" => url("packagerefereebook" , "spider")
            ),
            "任務管理" => array(
                "新增任務" => url("addtask" , "spider"),
                "尚未開始" => url("idletask" , "spider"),
                "正在進行中" => url("processingtask" , "spider"),
                "已完成任務數量" => url("finishedtask" , "spider")
            ),
            "任務稽核" => array(
                "任務稽核" => url("audittask" , "spider"),
                "稽核管理" => url("audittaksmanager" , "spider")
            ),
            "分析器管理" => array(
                "上傳分析器" => url('uploadsplitter' , 'spider'),
                "稽核分析器" => url('checksplitter' , 'spider')
            ),
            "帳號管理" => array(
                "新增帳號" => "#"
            ),
            "系統管理" => array(
                "分析器設定" => url('splittersetting' , 'spider')
            )
        );
    }

    /**
     *
     *
     * View 頁面
     *
     */

    public function login(){
    }

    public function index(){
        $this->isloginredirect();
        $this->_opdata['active'] = "Dashboard";
    }

    public function processingtask(){
        $this->isloginredirect();
        $this->_opdata['active'] = "正在進行中";
        $this->_opdata['task'] = $this->_model->getprocessingtask();
    }

    public function finishedtask(){
        $this->isloginredirect();
        $this->_opdata['active'] = "已完成任務數量";
        $this->_opdata['task'] = $this->_model->getFinishedTaskList();
    }

    public function idletask(){
        $this->isloginredirect();
        $this->_opdata['active'] = "尚未開始";
        $this->_opdata['task'] = $this->_model->getidletask();
    }

    public function addtask(){
        $this->isloginredirect();
        $this->_opdata['active'] = "新增任務";
        $this->_opdata['court'] = $this->_model->getCourt();
        $this->_opdata['type'] = $this->_model->getType();
    }

    public function audittask(){
        $this->isloginredirect();
        $isaudit = $this->_model->getRunAudit()['key'];
        if($this->_request->isPost()){
            if($isaudit == 0){
                $this->_model->setRunAudit(1);
                shell_exec("php " . ROOT."/index.php audit > /dev/null &" );
            }
            $this->_opdata['active'] = "任務稽核";
            $this->_opdata['isaudit'] = 1;
        }else{
            $this->_opdata['active'] = "任務稽核";
            $this->_opdata['isaudit'] = $isaudit;
        }
    }

    public function audittaksmanager(){
        $this->isloginredirect();
        $this->_opdata['active'] = "稽核管理";
        $this->_opdata['data'] = $this->_model->getSummaryOfAuditTask();
        $this->_opdata['isaudit'] = $this->_model->getRunAudit()['key'];
        if(empty($this->_opdata['data']['list'])){
            $this->setCustomView('audittaksmanagernodata');
        }
    }

    public function uploadsplitter(){
        $this->isloginredirect();
        $this->_opdata['active'] = "上傳分析器";
    }

    public function checksplitter(){
        $this->isloginredirect();
        $this->_opdata['active'] = "稽核分析器";
        $this->_opdata['splitter'] = $this->_model->getNonCheckSplitterList();
    }

    public function splittersetting(){
        $this->isloginredirect();
        $this->_opdata['active'] = "分析器設定";
        $this->_opdata['splitterList'] = $this->_model->getCheckSpliterList();
    }

    public function qrbbyid(){
        $this->isloginredirect();
        $this->_opdata['active'] = "以ID查詢";

    }

    public function packagerefereebook(){
        $this->isloginredirect();
        $this->_opdata['active'] = "打包下載";
    }

    public function downloadrefereebookpackage(){
        $this->isloginredirect();
        $logArray = $_POST['log'];
        $refereebookArray = $_POST['refereebook'];

        $dataArray = array();

        if(!empty($logArray)){
            preg_match_all("/\d+/" , $logArray , $l);
            $logArray = $l[0];
            //$dataArray = array_merge($logArray , $dataArray);

            foreach($logArray as $key => $value){
                $data = $this->_model->getRefereebookPathByLog($value);
                $dataArray[] = ROOT . "/public/jupload/origin/" . $data['ccode'] . "/" . $data['tcode'] . "/" . $data['stdate'] . "/" . $data['fname'] . ".txt";
            }

        }

        if(!empty($refereebookArray)){
            preg_match_all("/\d+/" , $refereebookArray , $l);
            $refereebookArray = $l[0];
            //$dataArray = array_merge($refereebookArray , $dataArray);

            foreach($refereebookArray as $key => $value){
                $data =  $this->_model->getRefereebookPath($value);
                $dataArray[] = ROOT . "/public/jupload/origin/" . $data['ccode'] . "/" . $data['tcode'] . "/" . $data['stdate'] . "/" . $data['fname'] . ".txt";
            }
        }

        $zip = new ZipArchive();
        $zip_name = sys_get_temp_dir() . "/" . time() . ".zip";
        $zip->open($zip_name , ZipArchive::CREATE);
        foreach($dataArray as $file){
            if(file_exists($file)){
                $zip->addFromString(pathinfo($file)['filename'] . "." . pathinfo($file)['extension'] , file_get_contents($file) );
            }
        }
        $zip->close();

        header("Content-Type: application/zip");
        header("Content-disposition: attachment; filename=refereebookPackage.zip");
        header("Content-Length: " . filesize($zip_name));
        readfile($zip_name);
        exit;
    }

    public function douploadsplitter(){
        $this->isloginredirect();
        $file = $_FILES['splitter'];
        $filename =  "splitter" .  base64_encode(time()) . "." .pathinfo($file['name'] , PATHINFO_EXTENSION) ;
        move_uploaded_file($file['tmp_name'] , ROOT . "/public/splitter/" .$filename  );

        $this->_model->uploadSplitter($filename);

        redirect("uploadsplitter" , "spider" , array("message" => "上傳成功"));
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function dochecksplitter(){
        $this->isloginredirect();
        $id = $this->_request->getPost('splitter');
        $this->_model->checksplitter($id);
        redirect('checksplitter');
    }

    public function doaddtask(){
        $this->isloginredirect();
        $court = $_POST['court'];
        $type = $this->_request->getPost("type");
        $startDate = $this->_request->getPost("startdate");
        $endDate =  $this->_request->getPost("enddate");

        foreach($court as $key => $value){
            if(!$this->_model->addNewTask($startDate , $endDate , $value , $type)){
                $this->_opdata['message'] .=  "court ID: ".$value . "," . "type: " . $type . ", " . $startDate . "~" . $endDate ." 任務已存在\r\n";
            }else{
                $this->_opdata['message'] .= "court ID: ".$value . "," . "type: " . $type . ", " . $startDate . "~" . $endDate . " 新增成功\r\n";
            }
        }
        $this->_opdata['message'] = nl2br($this->_opdata['message']);

        $this->_opdata['court'] = $this->_model->getCourt();
        $this->_opdata['type'] = $this->_model->getType();

        $this->setCustomView('addtask');
        //return self::AUTO_SHOWVIEW_OFF;
    }

    public function dologin(){
        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');

        if(($username == "admin" && $password == "meigic1212") || ($username == "TCA" && $password == "030f23")){
            $_SESSION['islogin'] = true;
        }
        redirect('index','spider');
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function logout(){
        $this->isloginredirect();
        unset($_SESSION['islogin']);
        redirect('index','spider');
        return self::AUTO_SHOWVIEW_OFF;
    }

    private function islogin(){
        if($_SESSION['islogin'] == true){
            return true;
        }else{
            return false;
        }
    }

    private function isloginredirect(){
        if(!$this->islogin()){
            redirect("login" , "spider");
        }
    }

    // ------------------------------------------------------------------------------------
    // API
    // ------------------------------------------------------------------------------------
    public function getsubtask(){
        api();
        api_json();
        $id = $this->_request->getQuery('id');
        echo json_encode($this->_model->getSubTaskByID($id));
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getlognumapi(){
        api();
        api_json();
        $data = $this->_model->getLogNum();
        echo json_encode($data);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getaveragespenttime(){
        api();
        api_json();
        $data = $this->_model->getAverageSpendTime();
        $result = array();
        for($i = 0 ; $i < count($data) ; $i++){
            $result[] = array(
                (int)strtotime($data[$i]['finishtime']) * 1000,
                //$data[$i]['finishtime'],
                (double)$data[$i]['averageSpendTime']
            );
        }
        echo json_encode($result);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getunfinishsubtaskapi(){
        api();
        api_json();
        echo json_encode((int)($this->_model->getUnfinishSubTaskCount()['unfinishSubTaskCount']));
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getlastlogtime(){
        api();
        api_json();
        echo json_encode( date("H:i:s" ,$this->_model->getLastLogTime() ) );
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getserverstatus(){
        api();
        api_json();
        if(date("H") == "3"){
            // server repaired
            echo json_encode(2);
        }else{
            $str = shell_exec( "tail -n 1 " . ROOT. "/log/" . date("Ymd") . "_spider.log");
            $date = substr($str , 0 , 8);
            if($str){
                if( abs(strtotime(date("Y-m-d ") .  $date) - time()) < 300 ){
                    echo json_encode(0);
                }else{
                    echo json_encode(1);
                }
            }else{
                echo json_encode(1);
            }
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getcourtcountapi(){
        api();
        api_json();
        $data = $this->_model->getCourtCount();
        $result = array();

        foreach($data as $key => $value){
            $result[] = array(
                "name" => $value['name'],
                "y" => (int)$value['total'],
                "drilldown" => substr($value['name'] , 0 , 3)
            );
        }
        echo json_encode($result);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getcourtcountdrilldownapi(){
        api();
        api_json();
        $datas = $this->_model->getCourtCountCountList();
        $result = array();
        foreach($datas as $key => $value){
            $datas2 = $this->_model->getCourtDateCount($value['code']);
            $data = array();
            foreach($datas2 as $key2 => $value2){
                $data[] = array(
                    strtotime($value2['date'])*1000,
                    (int)$value2['total']
                );
            }

            $result[] = array(
                "id" => $value['code'],
                "data" => $data
            );
        }
        echo json_encode($result);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getissplitterrun(){
        api();
        api_json();
        $result = (int)$this->_model->runSplitter()['key'];
        echo json_encode($result);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function loadsplitter(){
        api();
        api_json();
        $filename = $this->_request->getPost('filename');
        if(preg_match("/^[A-Za-z0-9+\\\\]+={0,2}\.py$/", $filename, $output_array) == 1){
            if(file_exists(ROOT . "/public/splitter/" . $filename)){
                $result = file_get_contents(ROOT . "/public/splitter/" . $filename);
                echo json_encode($result);
            }else{
                header("HTTP/1.1 404 Not Found");
                return self::AUTO_SHOWVIEW_OFF;
            }
        }else{
            echo json_encode("ERROR");
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getrunsplitter(){
        api();
        api_json();
        echo json_encode($this->_model->getSplitter());
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getsplitterlogstatusapi(){
        api();
        utf8();
        api_json();
        $data = $this->_model->getSplitterLogStatus();
        $result = array(
            array('未分析',
                (int)$data['unsplitter']),
            array('分析成功',
            (int)$data['splittered']),
            array('分析失敗',
            (int)$data['splitterError'])
        );
        echo json_encode($result , 1);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function updatesplitterrun(){
        if($this->islogin()){
            $data = $this->_request->getPost('run');
            $this->_model->updateSplitterRun($data);
            echo $data;
        }else{
            header404();
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function updaterunsplitter(){
        if($this->islogin()){
            $data = $this->_request->getPost('id');
            $this->_model->setRunSplitterFile($data);
            echo $data;
        }else{
            header404();
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * API
     * 以Refereebook ID 或是 Log ID 查詢裁判書全文
     * @return int
     */
    public function getrefereebookfilebyid(){
        if(!$this->islogin()){
            header404();
        }else{
            $id = $this->_request->getPost('id');
            $type = $this->_request->getPost('type');
            if(empty($type) || empty($id)){
                header404();
                return self::AUTO_SHOWVIEW_OFF;
            }

            if($type == "refereebook"){
                $data = $this->_model->getRefereebookPath($id);
            }else if($type == "log"){
                $data = $this->_model->getRefereebookPathByLog($id);
            }else {
                header404();
                return false;
            }

            if($data == null){
                header404();
            }else{
                api();
                utf8();
                api_json();
                $path = ROOT . "/public/jupload/origin/" . $data['ccode'] . "/" . $data['tcode'] . "/" . $data['stdate'] . "/" . $data['fname'] . ".txt";
                $fdata = file_get_contents($path);
                $result['data'] = $fdata;
                $result['path'] = $path;
                if($type == "refereebook"){
                    $result['dbdata'] = loadModel('api')->getCase($id);
                }
                echo json_encode($result);
            }
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function cleanauditreportdata(){

        $this->isloginredirect();

        $this->_model->cleanAuditData();

        redirect("audittaksmanager");

        return self::AUTO_SHOWVIEW_OFF;
    }

    public function rerunauditfailsubtask(){
        shell_exec("php " . ROOT."/index.php rerun > /dev/null &" );
        $this->_model->cleanAuditData();
        redirect("audittaksmanager");
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 產生 Base URL
     */
    public function baseurljs(){
        header("Content-Type:text/javascript; charset=utf-8");
        echo 'var baseURL = "' . WEB_ROOT . '/";';
        return self::AUTO_SHOWVIEW_OFF;
    }

}