<?php
App::uses('AppController', 'Controller');

class TimediffComponent extends Component{

    public $components = array('Auth');
    public $uses = 'Holiday';

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
    function time_diff_minus($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = strtotime($time_from) - $time_to;
        // 時間単位の和
        return "{$dif}";
    }

    function time_diff_sec($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = strtotime('1970-01-01 '.$time_to) - strtotime('1970-01-01 '.$time_from);
        // 時間単位の差

        $dif_day = (strtotime(date('Y-m-d',$dif)) - strtotime('1970-01-01'));
        return "{$dif_day}";
    }

    function time_add_sec($time_from, $time_to)
    {
        // 日時差を秒数で取得
        $dif = (strtotime('1970-01-01 '.$time_from) + strtotime('1970-01-01 '.$time_to)) - strtotime('1970-01-01 00:00:00');
        // 時間単位の差

        return "{$dif}";
    }
    // 時間の加算
    function addvtime($time_from,$time_to){
        $array1 = explode(':',$time_from);
        $array2 = explode(':',$time_to);
        return date('H:i',mktime($array1[0]+$array2[0],$array1[1]+$array2[1]));
    }

	function explode_time($time) {
		$time = explode(':', $time);
		$time = $time[0] * 3600 + $time[1] * 60;

		return $time;
	}

	function second_to_hhmm($time) {
		$hour = floor($time / 3600);
		$minute = strval(floor(($time % 3600) / 60));
		if ($hour == 0) {
			$hour = '00';
		} else {
			$hour = sprintf('%02d', $hour);
		}
		if ($minute == 0) {
			$minute = '00';
		} else {
			$minute = sprintf('%02d', $minute);
		}
		$time = $hour . ':' . $minute;

		return $time;
	}

	function addvtime_25($time1,$time2) {
		if (!$time1) {
			$time1 = '00:00:00';
		}
		if (!$time2) {
			$time2 = '00:00:00';
		}
		$time = $this->explode_time($time1) + $this->explode_time($time2);
		$result = $this->second_to_hhmm($time);

		return $result;
	}

    // 時間の減算
    //2023-08-04 差し引きが２４になると、mktimeで0になってしまう。
    function subvtime($time_from,$time_to){
        $array1 = explode(':',$time_from);
        $array2 = explode(':',$time_to);

        return date('H:i',mktime($array1[0]-$array2[0],$array1[1]-$array2[1]));
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
        //        return array('h'=>$hour,'i'=>$minutes,'s'=>$seconds);
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

    function checkw5($datetime){ // 当日５時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0');
    }
    function checkw22($datetime) { //当日２２時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0');
    }
    function checkw23($datetime) { //当日２３時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'23:0:0');
    }
    function checkw1n($datetime) { //翌日１時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'1:0:0')+(24*60*60);
    }
    function checkw5n($datetime) { //翌日の５時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0')+(24*60*60);
    }
    function checkw22p($datetime) { //先日の２２時
        $w_date = explode('-',date('Y-m-d-H-i-s',$datetime));
        return strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0')-(24*60*60);
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
        //    	return sprintf("%02d",$zan['h']).':'.sprintf("%02d",$zan['i']).':'.sprintf("%02d",$zan['s']);
        return sprintf("%02d",$zan['h']).':'.sprintf("%02d",$zan['i']);
    }

    //時分チェック（h:i形式）
    function chg_time_hi($v){
        //値が配列で入って来るケースがあるので対策
        if(is_array($v)){
            $v=reset($v);
        }
        $v=trim($v);
        $ary=explode(':',$v);
        $h=trim($ary[0]);
        $m=trim($ary[1]);

        if(!preg_match('/^[0-9]+$/', $h)){
            return false;
        }
        if(!preg_match('/^[0-9]+$/', $m)){
            return false;
        }
        if($h < 0 || $h > 23){
            return false;
        }
        if($m < 0 || $m > 59){
            return false;
        }
        $hm = $h.':'.$m;
        return $hm;
    }
    //残業時間を整数に変更
    function chg_integer($v){
        $v=trim($v);
        $ary=explode(':',$v);
        $h=trim($ary[0]);
        $m=trim($ary[1]);
        $m2 = $m / 60;
        $hm = $h + $m2;
        return $hm;
    }

}
