<?php

use phpDocumentor\Reflection\Types\Integer;

App::uses('AppHelper', 'View/Helper');

class FormatHelper extends AppHelper {

	// public function AddVtime($a,$b) {
	// 	if (isset($a)) {
	// 	} else {
	// 		$a = '00:00';
	// 	}
	// 	if (isset($b)) {
	// 		$c = $b;
	// 	} else {
	// 		$c = '00:00';
	// 	}
	// 	$aArry = explode(':',$a);
	// 	$bArry = explode(':',$c);

	// 	$time = date('H:i',mktime($aArry[0]+$bArry[0],$aArry[1]+$bArry[1]));
	// 	if ($time == '00:00') {
	// 		$time2 = '';
	// 	} else {
	// 		$time2 = $time;
	// 	}
	// 	return [0 => $time, 1 => $time2];
	// }

	public function explode_time($time) {
		$time = explode(':', $time);
		$time = $time[0] * 3600 + $time[1] * 60;

		return $time;
	}

	public function second_to_hhmm($time) {
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

	// pdfhost000,pdfhost000_2,pdfhost000_3,pdfhost000_5,HostS010s/csvで使用

	public function Addtime($time1,$time2) {
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
	public function Subtime($time1,$time2) {
	    if (!$time1) {
	        $time1 = '00:00:00';
	    }
	    if (!$time2) {
	        $time2 = '00:00:00';
	    }
	    $time = $this->explode_time($time1) - $this->explode_time($time2);
	    $result = $this->second_to_hhmm($time);

	    return $result;
	}

	// pdfhost000_3で使用
	public function SubtractTime($date1,$time1,$date2,$time2) {
		if (!$time1) {
			$time1 = '00:00:00';
		}
		if (!$time2) {
			$time2 = '00:00:00';
		}

        // 日時差を秒数で取得
        $time_from = $date1.' '.$time1;
        $time_to = $date2.' '.$time2;
        $dif = strtotime($time_to) - strtotime($time_from);
        // 時間単位の差
        $hour = floor($dif / 3600);
        $minutes = floor(($dif / 60) % 60);
        $result = substr('00'.$hour,-2).':'.substr('00'.$minutes,-2);

        return $result;

/*
		if ($time1 < $time2) {
			$time = $this->explode_time($time2) - $this->explode_time($time1);
		} else {
			$time = $this->explode_time($time1) - $this->explode_time($time2);
		}
		$result = $this->second_to_hhmm($time);
		return $result;
*/
	}

	// pdfhost000_5で使用
	public function AverageTime($time1,$time2) {
		if (!$time1) {
			$time1 = '00:00:00';
		}
		if (!$time2) {
			$time2 = '00:00:00';
		}
		if ($time1 == '00:00' || $time2 == '00:00' || $time1 == '00:00:00' || $time2 == '00:00:00') {
			//2022-03-09 $i = 1;
			$i = 2;
		} else {
			$i = 2;
		}
		$time = ($this->explode_time($time1) + $this->explode_time($time2)) / $i;
		$result = $this->second_to_hhmm($time);

		return $result;
	}

	// pdfhost000,pdfhost000_2,pdfhost000_3,pdfhost000_5で使用
	public function TimeFormat($time) {
		if ($time) {
			$time = $this->explode_time($time);
			$result = $this->second_to_hhmm($time);
		} else {
			$result = '00:00';
		}

		if ($result < 0) {
			$result = '-';
		}

		return $result;
	}

	// pdfhost000で使用
	public function TimeFormat_2($time) {
		if ($time) {
			$time = $this->explode_time($time);
			$result = $this->second_to_hhmm($time);
		} else {
			$result = '';
		}

		if ($result < 0) {
			$result = '-';
		} elseif ($result == '00:00:00' || $result == '00:00') {
			$result = '';
		}

		return $result;
	}

	// pdfhost000_3で使用
	public function NumberFormat($value) {
		if ($value) {
			$result = number_format($value);
		} else {
			$result = '';
		}

		return $result;
	}

	// pdfhost000_2,pdfhost000_3で使用
	public function If_null($value) {
		if($value) {
			$data = $value;
		} else {
			$data = '';
		}

		return $data;
	}

	// Dr050sで使用
	public function Addtime_2($time1,$time2) {
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

	// Dr050sで使用
	public function TimeFormat_3($time) {
		if ($time) {
			$time = $this->explode_time($time);
			$result = $this->second_to_hhmm($time);
		} else {
			$result = '00:00';
		}

		if ($result < 0) {
			$result = '-';
		}

		return '<td>'.$result.'</td>';
	}

	// Dr050sで使用
	public function If_null_2($value) {
		if($value) {
			$result = $value;
		} else {
			$result = '';
		}

		return '<td>'.$result.'</td>';
	}


	// HostS010s/csvで使用
	public function Subtime_csv($time1,$time2) {
	    if (!$time1) {
	        $time1 = '00:00:00';
	    }
	    if (!$time2) {
	        $time2 = '00:00:00';
	    }
	    $time = $this->explode_time($time1) - $this->explode_time($time2);
	    $result = $this->second_to_hhmm($time);

	    return $result;
	}
	//残業時間を整数に変更
	public function chg_integer($v){
	    $v=trim($v);
	    $ary=explode(':',$v);
	    $h=trim($ary[0]);
	    $m=trim($ary[1]);
	    $m2 = $m / 60;
	    $hm = $h + $m2;
	    return $hm;
	}

	public function chg_time($v){
	    $v = $v * 1;
	    if(is_float($v)){
	        $h = floor($v);
	        $w_float = $v - $h;
	        $m = $w_float * 60;
	        /*
	        $ary=explode('.',$v);
	        $h = $ary[0];
            $m = $ary[1]*60 / 100;
            */
	    }else{
	        $h = $v;
	        $m = '00';
	    }
	    return $h.':'.$m;
	}
}
