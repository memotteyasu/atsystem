<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CsvsController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Csv'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
			$this->set('user',$user);
			return true;
		}
		return false;
		break;
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('csvs',$this->Csv->find('all'));

		$now = new DateTime();

		$datestart = $now->format('Y-m-d');
		$dateend = $now->format('Y-m-d');
		$timestart = '16:00';
		$timeend = '23:00';
		$shinyastarttime = '22:00';
		$shinyaendtime = '05:00';

		debug([
			'datestart' => $datestart,
			'dateend' => $dateend,
			'starttime' => $timestart,
			'endtime' => $timeend,
			'shinyastarttime' => $shinyastarttime,
			'shinyaendtime' => $shinyaendtime,
		]);

		// ex.23:00~04:00
		// 24:00-23:00=01:00
		// 00:00+04:00=04:00
		// 01:00+04:00=05:00←残業時間
		if ($timestart <= $shinyastarttime) {
			$time_1 = $shinyastarttime;
		} else {
			$time_1 = $timestart;
		}
		if ($timeend >= $shinyaendtime) {
			$time_2 = $shinyaendtime;
		} else {
			$time_2 = $timeend;
		}

		if ($timestart <= $shinyastarttime && $timeend >= $shinyaendtime && $timestart <= $shinyaendtime && $timeend >= $shinyastarttime) {

		}

		$zanjikan_1 = $this->explode_time('24:00:00') - $this->explode_time($time_1);
		$zanjikan_2 = $this->explode_time('00:00:00') + $this->explode_time($time_2);
		$zanjikan_3 = $zanjikan_1 + $zanjikan_2;

		$zanjikan_3 = $this->second_to_hhmm($zanjikan_3);

		debug([
			'zanjikan_1' => $zanjikan_1,
			'zanjikan_2' => $zanjikan_2,
			'zanjikan_3' => $zanjikan_3
		]);
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

	function import(){
		if (!empty($this->data)) {
			$model =& $this->Csv;
			/* @var $model result */
			$up_file = $this->data['Csv']['csvFile']['tmp_name'];
			$fileName = './csv/'.$this->data['Csv']['csvFile']['name'];
			debug($fileName);
			if (is_uploaded_file($up_file)){
				move_uploaded_file($up_file, $fileName);
				$model->loadFromCSV($fileName);
				$this->Flash->success('データをアップロードしました。');
				// $this->redirect(['action'=>'index']);
			}
		}
	}

}
