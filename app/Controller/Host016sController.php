<?php
App::uses('AppController', 'Controller');
/**
 * Host016s Controller
 *
 * copy Host014 ４時間連続＆速度超過一覧
 */
class Host016sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
	public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster');
	public $components = array('Search.Prg','RequestHandler','Timediff','SelectData');
	public $presetVars = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->page = 'Host016s';

		// 検索対象のフィールド設定代入
		$this->presetVars = $this->User->presetVars;
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 11: //管理者
				case 12: //管理者
				case 20; //事務職（石油）
				case 21; //事務職（化学品）
				case 22; //事務職（ガス）
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職兼運転手
				$this->set('user', $user);
				return true;
				break;
			}
		}
		return false;
	}
	/*
 * index method
 */
	public function index() {
		$page = $this->page;

		$flgmasters = $this->Flgmaster->flgchk(1);
		$this->set(compact('flgmasters'));

		$branchmasters_id = $this->branchmasters_id;
		$departmentmasters_id = $this->Auth->user('departmentmasters_id');

		$date = new DateTime();
		$date->modify('first day of this months');
		$date2 = new DateTime();
		$date2->modify('last day of this months');

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));
			$branchmasters_id = $this->request->data('branchmasters_id');
			$departmentmasters_id = $this->request->data('departmentmasters_id');
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')
				],
				'date2' => [
					'year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')
				],
				'bid' => $branchmasters_id,
				'did' => $departmentmasters_id,
				'Page' => $page
			];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
				$departmentmasters_id = $this->Session->read('BackData2.did');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$this->set(compact('branchmasters_id','departmentmasters_id'));

		$paramsDate1 = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];

		$users =  $this->SelectData->data_select_conditionsHost016s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);

		if ($this->branchmasters_id == 1) {
			// 本社所属の場合、部署の全てを表示
			$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		} else {
			// 本社以外の場合、自身の所属部署のみを表示
			$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
				'conditions' => [
					'kaisyas_id' => $this->kaisyas_id,
					'id' => $this->branchmasters_id
				],
				'order' => ['id' => 'asc']
			]);
		}
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('users','branchmasters','departmentmasters','paramsDate1','paramsDate2','week'));
	}



}
