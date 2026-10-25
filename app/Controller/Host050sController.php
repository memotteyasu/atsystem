<?php
App::uses('AppController', 'Controller');

class Host050sController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Format','Session','Js' => ['Jquery']);
    public $uses = ['User','Kaisya','Branchmaster','Departmentmaster', 'Kintaitran','Flgmaster'];
	public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
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
	            case 26; //事務職（）
	            case 27; //事務職（）
	            case 28; //事務職兼運転手
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
	    $branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$this->branchmasters_id,$this->kaisyas_id);
	    $departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->kaisyas_id);
	    $this->set(compact('branchmasters','departmentmasters'));

	    if ($this->request->is('post')) {
	        $date = new DateTime($this->request->data('selectym1'));
	        $branchmasters_id = $this->request->data('branchmasters_id');
	        $departmentmasters_id = $this->request->data('departmentmasters_id');
	        $backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'did' => $departmentmasters_id];
	        $this->Session->write('BackData1', $backdatas);
//	        return $this->redirect(['action' => 'index']);
	    }else{
	        $date = new DateTime();
	        $branchmasters_id = $this->branchmasters_id;
	        $departmentmasters_id = $this->Auth->user('departmentmasters_id');
	    }
	    $paramsDate = $date->format('Y-m-d');
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set(compact('paramsDate','week','departmentmasters_id','branchmasters_id'));

	    $users = $this->SelectData->data_select_conditionsHost050s($paramsDate,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
	    $this->set('users', $users);
	}

}
