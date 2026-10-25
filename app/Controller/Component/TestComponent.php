<?php
App::uses('AppController', 'Controller');

class TestComponent extends Component{

    public $components = array('Auth');
	public $uses = ['Holiday','Truckkind'];

	function date_select($selectdate) {
		$tyy1 = $selectdate['year'];
		$tmm1 = $selectdate['month'];
		$tdd1 = $selectdate['day'];
		$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		return $date;
	}

	function shipper_select($sid) {
		$shippermasters_id = $sid;
		return $shippermasters_id;
	}

	function productdetail_select($pid) {
		$productdetailmasters_id = $pid;
		return $productdetailmasters_id;
	}

	function data_select_conditions($paramsDate,$paramsDate2,$shippermasters_id,$productdetailmasters_id) {
		$userModel = ClassRegistry::init('Truckkind');
		$userModel2 = ClassRegistry::init('Productdetailmaster');

		if (!$shippermasters_id && !$productdetailmasters_id) {
			$truckkinds = [];
			$productdetailmasters = $userModel2->pdnameList(4);
		} elseif (!$shippermasters_id) {
			$truckkinds = $userModel->SelectTruckkindProductdetail($paramsDate,$paramsDate2,$productdetailmasters_id);
			$productdetailmasters = $userModel2->pdnameList(4);
		} elseif (!$productdetailmasters_id) {
			$truckkinds = $userModel->SelectTruckkindShipper($paramsDate,$paramsDate2,$shippermasters_id);
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		} elseif ($shippermasters_id && $productdetailmasters_id) {
			$truckkinds = $userModel->SelectTruckkindAll($paramsDate,$paramsDate2,$shippermasters_id,$productdetailmasters_id);
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		}

		//debug($truckkinds);
// 		debug($paramsDate);
// 		debug($paramsDate2);
// 		debug($productdetailmasters_id);
// 		debug($shippermasters_id);
		return ['truckkinds' => $truckkinds, 'pdlist' => $productdetailmasters];
	}

    /* 開始時間と終了時間の差を計算 */
    function time_diff($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = strtotime($time_to) - strtotime($time_from);
        // 時間単位の差
        return "{$dif}";
    }

    function time_diff_add($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = strtotime($time_from) + $time_to;
        // 時間単位の和
        return "{$dif}";
    }

    function time_diff_sec($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = strtotime('1970-01-01 '.$time_to) - strtotime('1970-01-01 '.$time_from);
        // 時間単位の差

        $dif_day = (strtotime(date('Y-m-d',$dif)) - strtotime('1970-01-01'));
        return "{$dif}";
    }

    /* 定時の算出 */
    function time_teiji($mm, $dd)
    {
        $mmdd = $mm.$dd;
        if(($this->Auth->user('Kaisya.summertimestart') <= $mmdd)and($this->Auth->user('Kaisya.summertimeend') >= $mmdd)){
            $teiji = $this->Auth->user('Departmentmaster.jobtearmsummertime');
        }else{
            $teiji = $this->Auth->user('Departmentmaster.jobtermtime');
        }
        return"{$teiji}";
    }
    /* 平日休日のチェッック */
    function holiday_check($yy,$mm,$dd)
    {
        $ymd = date('Y-m-d',mktime(0,0,0,$mm,$dd,$yy));
        $userModel = ClassRegistry::init('Holiday');
        $holidaycheck = $userModel->find('first',[
            'conditions'=>[
                'startdate'=>$ymd
            ]
        ]);
        return array($holidaycheck);
    }
    /* 秒をHisに変換 */
    function his_change($worktime){
        $hour = floor($worktime / 3600);
        $minutes = floor(($worktime / 60) % 60);
        $seconds = $worktime % 60;
        return array('h'=>$hour,'i'=>$minutes,'s'=>$seconds);
    }
    /* Hisを秒に変換 */
    function sec_change($worktime){
        $w_sec = strtotime('1970-01-01 '.$worktime) - strtotime('1970-01-01 0:0:0');
        return"{$w_sec}";
    }

    function datetime($date,$time) {
    	return $date.' '.$time;
    }

    function w5($startdatetime) {
    	$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($startdatetime)));
    	return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0');
    }

    function w22($startdatetime) {
    	$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($startdatetime)));
    	return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0');
    }

    function w5n($startdatetime) {
    	$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($startdatetime)));
    	return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0')+(24*60*60); // 翌朝5時
    }

    function sprintf($zan) {
    	return sprintf("%02d",$zan['h']).':'.sprintf("%02d",$zan['i']).':'.sprintf("%02d",$zan['s']);
    }

}
