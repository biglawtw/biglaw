<?php

/** @property lib_mysql $_db */
class SpiderModel extends Model
{
    /**
     * 新增法院
     * @param $code
     * @param $cour
     */
    function insertCourt($code, $court)
    {
        $data = array(
            'code' => $code,
            'name' => $court
        );
        $this->_db->insert("Court", $data);
    }

    /**
     * 取得法院列表
     * @return mixed
     */
    function getCourt()
    {
        $data = $this->_db->getAllData('Court');
        return $data;
    }

    /**
     * 取得裁判類別
     * @return mixed
     */
    function getType()
    {
        $data = $this->_db->getAllData('Type');
        return $data;
    }

    /**
     * 新增新的爬蟲任務
     * @param $startTime
     * @param $endTime
     * @param $court
     * @param $type
     * @return bool
     */
    function addNewTask($startTime, $endTime, $court, $type)
    {
        $sql = "SELECT count(SubTask.id) as num from SubTask Left JOIN Task on SubTask.TaskID = Task.id WHERE Task.court = $court and Task.Type=$type and (SubTask.date = '$startTime' or SubTask.date = '$endTime')";
        $this->_db->query($sql);
        $data = $this->_db->getDatas();

        if ($data[0]['num'] == 0) {
            $data = array(
                "startDate" => date("Ymd", strtotime($startTime)),
                "endDate" => date("Ymd", strtotime($endTime)),
                "court" => $court,
                "type" => $type,
                "addTime" => date("Y-m-d H:i:s", time())
            );

            $taskID = $this->_db->insert("Task", $data);


            $d = strtotime($startTime);

            while (true) {
                $subTaskData = array(
                    "TaskID" => $taskID,
                    "date" => date("Ymd", $d)
                );
                $this->_db->insert("SubTask", $subTaskData);
                $dt = new DateTime(date("Y-m-d", $d));
                $d = $dt->add(new DateInterval("P1D"))->getTimestamp();
                if ($d > strtotime($endTime)) {
                    break;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 取得尚未完成的爬蟲任務清單
     * @return mixed
     */
    function getUnFinishedList()
    {
        $sql = <<<UNFINISH
SELECT
	Task.id,
	SubTask.id AS SubTaskID,
	SubTask.date,
	SubTask.total,
	SubTask.processing,
    Court.code AS CourtCode,
	Court.name AS CourtName,
	Type.code AS SYSCode,
	Type.name AS SYSName
FROM
	Task
LEFT JOIN SubTask ON Task.id = SubTask.TaskID
LEFT JOIN Court ON Task.court = Court.id
LEFT JOIN Type ON Task.type = Type.id
WHERE
	(SubTask.finishtime IS NULL);
UNFINISH;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 取得現在正在處理中的爬蟲任務
     * @return mixed
     */
    function getprocessingtask()
    {
        $sql = <<<PROCESSINGTASK
SELECT Task.id , Task.startDate , Task.endDate , Court.name AS CourtName, Type.name AS TypeName , Task.addTime , Task.finishTime
FROM SubTask LEFT JOIN Task on SubTask.TaskID = Task.id
LEFT JOIN Court on Task.court = Court.id LEFT JOIN Type on Type.id = Task.type
WHERE SubTask.finishTime IS NULL and SubTask.startTime IS NOT NULL
PROCESSINGTASK;
        $this->_db->query($sql);
        return $this->_db->getDatas();

    }

    /**
     * 取得尚未開始的爬蟲任務
     * @return mixed
     */
    function getidletask()
    {
        $sql = <<<IDLETASK
SELECT DISTINCT Task.id , Task.startDate , Task.endDate , Court.name as CourtName , Type.name as TypeName , Task.addTime , count(SubTask.id) as SubTaskCount
FROM SubTask LEFT JOIN Task on SubTask.TaskID = Task.id
LEFT JOIN Court on Court.id = Task.court
LEFT JOIN Type on Type.id = Task.type
WHERE startTime IS NULL
GROUP BY Task.id
IDLETASK;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 取得未完成的爬蟲任務狀態
     * 未完成任務ID , 完成時間 , 完成子任務數量數量 , 未完成子任務數量
     * @return mixed
     */
    function gettaskstate()
    {
        $sql = <<<STATE
SELECT A.id, A.finishTime, IFNULL( B.finishCount,0) as finishCount , IFNULL(C.unfinishCount , 0) as unfinishCount
FROM Task A
LEFT JOIN (
    SELECT A.id, count( B.id ) AS finishCount
    FROM Task A
    LEFT JOIN SubTask B ON A.id = B.TaskID
    WHERE B.finishTime IS NOT NULL
    GROUP BY A.id
)B ON A.id = B.id
LEFT JOIN (
    SELECT A.id, count( B.id ) AS unfinishCount
    FROM Task A
    LEFT JOIN SubTask B ON A.id = B.TaskID
    WHERE B.finishTime IS NULL
    GROUP BY A.id
)C ON A.id = C.id
WHERE A.finishTime IS NULL
STATE;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 更新爬蟲子任務的資料總數
     * @param $id
     * @param $total
     */
    function updateSubTaskTotal($id, $total)
    {
        $data = array(
            "id" => $id,
            "total" => $total,
            "processing" => 1
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * 更新爬蟲子任務的處理筆數
     * @param $id
     * @param $process
     */
    function updateSubTaskProcessing($id, $process)
    {
        $data = array(
            "id" => $id,
            "processing" => $process
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * 完成一個爬蟲子任務
     * @param $id
     */
    function finishSubTask($id)
    {
        $data = array(
            "id" => $id,
            "finishtime" => date("Y-m-d H:i:s", time())
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * 插入一筆裁判書到Log
     * @param $id
     * @param $file
     */
    function insertlog($id, $file)
    {
        $data = array(
            'SubTaskID' => $id,
            'Filename' => $file
        );
        $this->_db->insert("Log", $data);
    }

    /**
     * 完成一個爬蟲任務
     * @param $id
     */
    function updateTaskToFinish($id)
    {
        $data = array(
            'id' => $id,
            'finishTime' => date("Y-m-d H:i:s", time())
        );
        $this->_db->update("Task", $data);
    }

    /**
     * 開始進行某個爬蟲子任務
     * @param $id
     */
    function startSubTask($id)
    {
        $data = array(
            'id' => $id,
            'startTime' => date("Y-m-d H:i:s", time())
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * !!!!這個很慢
     * 查詢檔案重複的資料
     */
    function searchlog()
    {
        $sql = <<< morethanone
SELECT C.id , C.filename , C.countFilename
FROM
(SELECT
	A.id , A.filename ,
    (SELECT
     	count(*)
     FROM Log B
     WHERE B.filename = A.filename) as countFilename
FROM Log A) C
WHERE C.countFilename > 1
morethanone;
    }

    /**
     * 取得所有的任務清單
     * @return mixed
     */
    function getTaskList()
    {
        $sql = <<<TASKLISTSQL
SELECT
Task.id ,
Task.startDate ,
Task.endDate ,
Court.code ,
Court.name as CourtName ,
Type.name as TypeName,
Task.addTime ,
Task.finishTime
FROM Task Left join Court on Task.court = Court.id left join Type on Task.type = Type.id
TASKLISTSQL;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 取得完成的任務清單
     * @return mixed
     */
    function getFinishedTaskList()
    {
        $sql = <<<SQL
SELECT
Task.id ,
Task.startDate ,
Task.endDate ,
Court.code ,
Court.name as CourtName ,
Type.name as TypeName,
Task.addTime ,
Task.finishTime
FROM Task Left join Court on Task.court = Court.id left join Type on Task.type = Type.id
WHERE Task.finishTime IS NOT NULL;
SQL;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 計算平均消耗時間　- 最新２０筆資料
     * @return mixed
     */
    function getAverageSpendTime()
    {
        $sql = <<<AVERAGESPENDTIME
SELECT *
FROM (select  SubTask.* , TIME_TO_SEC( TIMEDIFF( DATE_FORMAT(finishTime, "%Y-%m-%d  %H:%i:%s") ,DATE_FORMAT(startTime, "%Y-%m-%d %H:%i:%s") ) ) as spendTime  , IFNULL( TIME_TO_SEC( TIMEDIFF( DATE_FORMAT(finishTime, "%Y-%m-%d %H:%i:%s")  ,DATE_FORMAT(startTime, "%Y-%m-%d %H:%i:%s") ) )/SubTask.total , 0) as  averageSpendTime
FROM SubTask
WHERE startTime <> 0 and TIME_TO_SEC( TIMEDIFF( DATE_FORMAT(finishTime, "%Y-%m-%d %H:%i:%s")  ,DATE_FORMAT(startTime, "%Y-%m-%d %H:%i:%s") ) )/SubTask.total <> 0
ORDER BY SubTask.id DESC LIMIT 20) A
ORDER BY id ASC
AVERAGESPENDTIME;
        $this->_db->query($sql);

        return $this->_db->getDatas();

    }

    /**
     * 取得指定任務的子任務列表
     * @param $id
     * @return mixed
     */
    function getSubTaskByID($id)
    {
        $sql = "SELECT * from SubTask WHERE TaskID=" . $id;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 取得目前資料總數
     * @return mixed
     */
    function getLogNum()
    {

        $sql = "SELECT count(*) as num FROM Log";
        $this->_db->query($sql);
        $result = $this->_db->getData();

        return $result['num'];
    }

    /**
     * 取得還沒分析的子任務
     * @return mixed
     */
    function getUnSplitterSubTask()
    {
        $sql = "SELECT * FROM SubTask WHERE audit = 2 and  splitter = 0 and finishtime is not null  ORDER BY SubTask.id LIMIT 0,1";
        $this->_db->query($sql);
        $result = $this->_db->getData();
        return $result;
    }

    /**
     * 用還沒分析的子任務取得資料列表
     * @param $id
     * @return mixed
     */
    function getUNSplitterLog($id)
    {
        $sql = "SELECT Log.id , Log.SubTaskID , Court.code as CourtCode , Type.code as TypeCode, SubTask.date , Log.Filename , Log.timelog , Log.statusCode , Log.errorCode FROM Log LEFT JOIN SubTask on Log.SubTaskID = SubTask.id LEFT JOIN Task on SubTask.TaskID = Task.id LEFT JOIN Court on Task.court = Court.id LEFT JOIN Type on Type.id = Task.type WHERE SubTaskID = " . $id;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 更新 Log資料內容
     * @param $id
     * @param $code
     */
    function updateSpliterLog($id, $code)
    {
        $data = array(
            'id' => $id,
            'StatusCode' => 2
        );
        if ($code == "E001") {
            $data['StatusCode'] = 3;
            $data['errorCode'] = 1;
        } else if ($code == "E002") {
            $data['StatusCode'] = 3;
            $data['errorCode'] = 2;
        } else if ($code == "E003") {
            $data['StatusCode'] = 3;
            $data['errorCode'] = 3;
        } else if ($code == "E004") {
            $data['StatusCode'] = 3;
            $data['errorCode'] = 4;
        } else if ($code == "E005") {
            $data['StatusCode'] = 3;
            $data['errorCode'] = 5;
        } else {
            $data['refereebook'] = $code;
        }
        $this->_db->update("Log", $data);
    }

    /**
     * 更新子任務為完成分析
     * @param $id
     */
    function updateSubTaskSplitter($id)
    {
        $data = array(
            'id' => $id,
            'splitter' => 1
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * 取得未完成的子任務數量
     * @return mixed
     */
    function getUnfinishSubTaskCount()
    {
        $sql = "SELECT count(*) as unfinishSubTaskCount FROM SubTask WHERE finishtime is null";
        $this->_db->query($sql);
        return $this->_db->getData();
    }

    /**
     * 取得最後Log 的時間
     * @return int
     */
    function getLastLogTime()
    {
        $sql = "SELECT Log.timelog FROM Log ORDER BY id DESC Limit 0,1";
        $this->_db->query($sql);
        return strtotime($this->_db->getData()['timelog']);
    }

    /**
     * 取得每個法院的資料總數
     * @return mixed
     */
    function getCourtCount()
    {
        $sql = <<<COURTCOUNT
SELECT Court.name , A.total
FROM Court ,
(SELECT Task.court , sum(SubTask.total) as total
FROM Task
LEFT JOIN SubTask on Task.id = SubTask.TaskID
GROUP BY Task.court) A
WHERE Court.id = A.court and A.total IS NOT NULL
COURTCOUNT;

        $this->_db->query($sql);

        return $this->_db->getDatas();
    }

    /**
     * 取得有資料的法院列表
     * @return mixed
     */
    public function getCourtCountCountList()
    {
        $sql = <<<GETCOURTCOUNTCOURTLIST
SELECT DISTINCT Court.`code` ,  Court.name
FROM Court ,
(SELECT Task.court , sum(SubTask.total) as total
FROM Task
LEFT JOIN SubTask on Task.id = SubTask.TaskID
GROUP BY Task.court) A
WHERE Court.id = A.court and A.total IS NOT NULL
GETCOURTCOUNTCOURTLIST;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function getCourtDateCount($code)
    {
        $sql = <<<COURTDATECOUNT
SELECT DISTINCT Court.name , A.date , A.total
FROM Court ,
(SELECT Task.court , SubTask.date , sum(SubTask.total) as total
FROM Task
LEFT JOIN SubTask on Task.id = SubTask.TaskID
GROUP BY Task.court , SubTask.date ) A
WHERE Court.id = A.court and A.total IS NOT NULL AND Court.code = "$code" ORDER BY A.date
COURTDATECOUNT;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 上傳新的分析器
     * @param $filename
     * @return mixed
     */
    function uploadSplitter($filename)
    {
        $data = array(
            "filename" => $filename,
            "uploadtime" => date("Y-m-d H:i:s", time())
        );
        return $this->_db->insert("Splitter", $data);
    }

    /**
     * 取得目前使用的分析器
     * @return mixed
     */
    function getSplitter()
    {
        $sql = <<<GETSPLITTER
SELECT Options.name , Splitter.id ,  Splitter.filename
FROM Options , Splitter
WHERE name = "splitter" and Splitter.id = Options.key and Splitter.ischeck = 1
GETSPLITTER;
        $this->_db->query($sql);
        $result = $this->_db->getData();
        return $result;
    }

    function setRunSplitterFile($id)
    {
        $sql = "UPDATE `Options` SET `key`='$id' WHERE name='splitter'";
        $this->_db->query($sql);
    }

    /**
     * 判斷分析器是否要跑?
     * @return mixed
     */
    function runSplitter()
    {
        $sql = "SELECT * FROM Options WHERE name = 'runSplitter'";
        $this->_db->query($sql);
        return $this->_db->getData();
    }

    /**
     * 取得稽核過的分析器
     * @return mixed
     */
    function getCheckSpliterList()
    {
        $sql = "SELECT * FROM Splitter WHERE ischeck = 1";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 取得尚未稽核的分析器
     * @return mixed
     */
    function getNonCheckSplitterList()
    {
        $this->_db->query("SELECT * FROM Splitter WHERE ischeck = 0");
        return $this->_db->getDatas();
    }

    /**
     * 稽核分析器
     * @param $id
     */
    function checksplitter($id)
    {
        $data = array(
            'id' => $id,
            'ischeck' => 1
        );
        $this->_db->update('Splitter', $data);
    }

    function updateSplitterRun($run)
    {
        $sql = "UPDATE `Options` SET `key`='$run' WHERE name='runSplitter'";
        $this->_db->query($sql);
    }

    /**
     * 取得分析狀態
     * @return mixed
     */
    function getSplitterLogStatus()
    {
        $sql = <<<SPLITTERLOGSTATUS
SELECT
SUM(IF(CAST(Log.statusCode AS UNSIGNED) = 0 , 1 , 0)) as unsplitter,
SUM(IF(CAST(Log.statusCode AS UNSIGNED) = 2 , 1 , 0)) as splittered,
SUM(IF(CAST(Log.statusCode AS UNSIGNED) = 3 , 1 , 0)) as splitterError
FROM Log
SPLITTERLOGSTATUS;
        $this->_db->query($sql);
        return $this->_db->getData();
    }


    /**
     * 新增一筆資料到稽核失敗清單
     * @param $data
     * @return mixed
     */
    function insertAudit($data)
    {
        return $this->_db->insert('Audit', $data);
    }

    /**
     * 取得資料庫中的Audit資料判斷是否要進行 任務稽核
     * @return mixed
     */
    function getRunAudit()
    {
        $sql = "SELECT * FROM Options WHERE `name`='runAudit'";
        $this->_db->query($sql);
        $data = $this->_db->getData();
        return $data;
    }

    /**
     *
     * @param $key
     */
    function setRunAudit($key)
    {
        $sql = "UPDATE Options SET `key`='$key' WHERE `name`='runAudit'";
        $this->_db->query($sql);
    }

    /**
     * 取得尚未稽核的任務清單
     * @return Array
     */
    function getUnAuditTaskList()
    {
        $sql = <<<GETUNAUDITTASKLIST
SELECT Task.id , Task.startDate , Task.endDate , Court.`code` , COUNT(SubTask.id)
FROM Task LEFT JOIN (SELECT * FROM SubTask WHERE (SubTask.audit = 0 or SubTask.audit = 1) and finishtime is not null) SubTask on Task.id = SubTask.TaskID
LEFT JOIN Court on Court.id = Task.court
WHERE Task.endDate IS NOT NULL
GROUP BY Task.id
HAVING COUNT(SubTask.id) > 0
GETUNAUDITTASKLIST;
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    /**
     * 設定子任務的稽核狀態
     * @param $id
     * @param $audit int 稽核狀態 1->失敗, 2->成功
     */
    function setSubTaskAudit($id, $audit)
    {
        $data = array(
            "id" => $id,
            "audit" => $audit
        );
        $this->_db->update("SubTask", $data);
    }

    /**
     * 取得稽核結果
     * @return array
     */
    function getSummaryOfAuditTask()
    {
        $sql = "SELECT (SELECT COUNT(*) FROM Audit) as totalMissSubTask , (SELECT SUM(`Real` - Total) as totalMiss FROM Audit) as TotalMissRefereebook;";
        $this->_db->query($sql);
        $result['summary'] = $this->_db->getData();
        $sql = "SELECT A.v as SubTaskMissCount , COUNT(A.v) as Count FROM (SELECT (`Real`-Total) as v FROM Audit) A GROUP BY A.v";
        $this->_db->query($sql);
        $result['list'] = $this->_db->getDatas();
        return $result;
    }

    /**
     * 以 xunsearch 的 pid 對應到 refereebook 的 ID 後，
     * 找出 整個檔案的路徑相關資訊
     * (CourtCode / TypeCode / Date / Filename . txt)
     * @param $id
     * @return mixed
     */
    function getRefereebookPath($id)
    {
        $sql = <<<GETREFEREEBOOKPATH
SELECT `Log`.id as logid , `refereebook`.ID as rid , `Log`.`Filename` as fname , `SubTask`.date as stdate , `Type`.`code` as tcode ,  `Court`.code as ccode FROM `refereebook`
LEFT JOIN `Log` on `Log`.refereebook = refereebook.`ID`
LEFT JOIN `SubTask` on `SubTask`.id = `Log`.`SubTaskID`
LEFT JOIN `Task` on `Task`.id = `SubTask`.`TaskID`
LEFT JOIN Court on Court.id = Task.court
LEFT JOIN Type on Type.id = Task.type
WHERE `refereebook`.ID = $id
GETREFEREEBOOKPATH;
        $this->_db->query($sql);
        return $this->_db->getData();
    }

    function cleanAuditData(){
        $sql = "TRUNCATE TABLE Audit";
        $this->_db->query($sql);
    }

    /**
     * 以 爬蟲的 Log.id
     * 找出 整個檔案的路徑相關資訊
     * (CourtCode / TypeCode / Date / Filename . txt)
     * @param $id
     * @return mixed
     */
    function getRefereebookPathByLog($id)
    {
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

}