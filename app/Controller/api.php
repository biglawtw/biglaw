<?php

require_once 'XS.php';

/** @property apiModel $_model */
Class apiController extends Controller
{

    public function hot(){
	api();
	api_json();

	try{
	    $xs = new XS('biglaw');
	    $search = $xs->search;
	    $hot = $search->getHotQuery();
	    echo json_encode($hot);
	}catch(Exception $e){
	    echo json_encode("error:notfound");
	}
	return self::AUTO_SHOWVIEW_OFF;
    }

    public function search()
    {
        api();
        api_json();
        $keyword = $_REQUEST['keyword'];
        $page = (empty($_REQUEST['page'])?1:$_REQUEST['page']);
        $html = $_REQUEST['html'];
        $fromDate = $_REQUEST['fromdate'];
        $toDate = $_REQUEST['todate'];

        Log::write("keyword:" . $keyword . " from-to:" . $fromDate . "-" . $toDate , 1 , "search");
        if(!empty($fromDate)){
            $from = strtotime($fromDate);
        }

        if(!empty($toDate)){
            $to = strtotime($toDate);
        }


        if (empty($keyword) || $keyword == "") {
            if(empty($fromDate) && empty($toDate)){
                echo json_encode("error:nokeyword");
                return self::AUTO_SHOWVIEW_OFF;
            }
        }

        try {

            $xs = new XS('biglaw');
            $search = $xs->search;
            $search->setCharset('UTF-8');
            //$search->setFuzzy(true);
            $search->setQuery($keyword);
            if(!empty($fromDate) && !empty($toDate)){
                $search->addRange("Judgedate" , date("Ymd" , $from) , date("Ymd" , $to) );
            }else if(!empty($fromDate)){
                $search->addRange("Judgedate" , date("Ymd" , $from) , null );
            }else if(!empty($toDate)){
                $search->addRange("Judgedate" , null , date("Ymd" , $to) );
            }
            $search->setLimit(10, ($page-1)*10);

            $docs = $search->search();
            $count = $search->getLastCount();

            if ($count == 0 || count($docs) == 0) {
                echo json_encode("error:emptyquery");
                return self::AUTO_SHOWVIEW_OFF;
            }

            $result = array();

            $resultlist = array();

            $ids = array();

            foreach ($docs as $key => $doc) {
                $ids[] = $doc->ID;
                $resultlist[] = array(
                    'caseid' => $doc->ID,
                    'casedate' => $doc->Judgedate,
                    'caseword' => $doc->Caseyear . "," . $doc->Character . "," . $doc->Number,
                    'casereason' => $doc->Judgereason,
                    'casecourt' => $doc->Location,
                    'caselaw' => $this->_model->getLaw($doc->ID),
                    'casesummary' => $doc->Main,
                    'caserank' => $doc->rank(),
                    'casepercent' => $doc->percent()
                );
            }

            $idsString = join(",", $ids);

            $result['count'] = $count;
            $result['list'] = $resultlist;
            if (empty($html)) {
                echo json_encode($result);
            } else {
                echo "<pre>";
                print_r($result);
                echo "</pre>";
            }
        } catch (Exception $excep) {
            echo json_encode("error:servererror");
            return self::AUTO_SHOWVIEW_OFF;
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getcase()
    {
        api();
        api_json();
        $id = $_REQUEST['id'];

        $data = $this->_model->getCase($id);

        if (is_array($data)) {
            echo json_encode($data);
            return self::AUTO_SHOWVIEW_OFF;
        } else {
            echo json_encode("error:nocase");
            return self::AUTO_SHOWVIEW_OFF;
        }
    }

}
