<?php
App::uses('AppController', 'Controller');
/**
 * Host013 Controller
 */
class Host013sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Branchmaster','Holiday','Kintaitran','Calendarusermaster');
	public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
	public $components = ['SelectData'];
/**
 * Components
 *
 * @var array
 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Host013s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
					$this->set('user', $user);
					return true;
					break;
			}
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
		$did = $this->did;
		$page = $this->page;
		$branchmasters_id = $this->branchmasters_id;

		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);

		$this->set(compact('branchmasters'));

        $date = new DateTime();
		// 先にSessionがある場合初期値を設定
		if ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				if ($this->Session->check('BackData2.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				}
				if ($this->Session->check('BackData2.bid')) {
					$branchmasters_id = $this->Session->read('BackData2.bid');
				}
			}
			$this->Session->delete('BackData2');
		} elseif ($this->Session->check('BackData1')) {
			if ($this->Session->read('BackData1.Page') == $page) {
				if ($this->Session->check('BackData1.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				}
				if ($this->Session->check('BackData1.bid')) {
					$branchmasters_id = $this->Session->read('BackData1.bid');
				}
			} else {
				$this->Session->delete('BackData1');
			}
		}
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));
			$branchmasters_id = $this->request->data('branchmasters_id');

			if (isset($this->request->data['lastweek'])) {
				$date->modify('-2 weeks');
			}
			if (isset($this->request->data['nextweek'])) {
				$date->modify('+2 weeks');
			}
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'),
					'month' => $date->format('m'),
					'day' => $date->format('d')
				],
				'bid' => $branchmasters_id,
				'Page' => $page
			];
			$this->Session->write('BackData1', $backdatas);
			return $this->redirect(['action' => 'index']);
		}

		$paramsDate = $date->format('Y-m-d');
		$firstDate = new \DateTime('first day of ' . $date->format('Y-m'));
		$paramsDate2 = $date->modify('+13 day')->format('Y-m-d');
		// $paramsDate2 = $testdate->modify('+13 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$userlists = $this->User->SelectUserHost013s($paramsDate,$paramsDate2,$branchmasters_id,$this->kaisyas_id);
		$selectkintais = $this->Kintaitran->SelectKintaitranHost013s($firstDate->format('Y-m-d'),$paramsDate,$branchmasters_id,$this->kaisyas_id);
		$this->set(compact('paramsDate','week','branchmasters_id','userlists','selectkintais'));
	}

}
