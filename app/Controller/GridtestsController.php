<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class GridtestsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = ['Gridtest','Productdetailmaster','Carmaster'];
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');

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

	public function index(){
		if ($this->request->is('post')) {
			$tyy1 = $this->data['selectym1']['year'];
			$tmm1 = $this->data['selectym1']['month'];
			$tdd1 = $this->data['selectym1']['day'];
			$productdetailmasters_id = $this->data['productdetailmasters_id'];
		}else{
			$tyy1 = date('Y');
			$tmm1 = date('m');
			$tdd1 = date('d');
			$productdetailmasters_id = null;
		}

		//$paramsDate = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		//$firstDate = $paramsDate->format('Y-m-d');

		$paramsDate = $tyy1.'-'.$tmm1.'-'.$tdd1;
		//$firstDate = strtotime($paramsDate);
		$week = ['日','月','火','水','木','金','土'];

		if ($productdetailmasters_id == null) {
			//$this->set('tests', $this->Gridtest->find('all',['recursive' => 2]));
			$this->set('tests', $this->Gridtest->Select2($tyy1,$tmm1,$tdd1));
		} else {
			$this->set('tests', $this->Gridtest->Select($tyy1,$tmm1,$tdd1,$productdetailmasters_id));
			//$this->set('tests', $this->Gridtest->Select2($tyy1,$tmm1,$tdd1,$tdd2));
		}

		//$this->set('tests', $this->Gridtest->Select($tyy1,$tmm1,$tdd1,$tdd2,$productdetailmasters_id));

		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$carmasters = $this->Carmaster->SelectList();
		$this->set(compact('productdetailmasters','carmasters','paramsDate','week'));

		/*
		$paramsdate = $tyy.'-'.$tmm.'-'.$tdd;
		$this->set(compact('tyy','tmm','tdd'));
		$this->set('gethost',$this->Oilordertran->getOilordertran($tyy,$tmm,$tdd));

		$this->set('timetable',$this->Timetablemaster->SelectTimetablemaster());
		$this->set('reservetran', $this->Reservetran->SelectReservetran($paramsdate));
		*/
	}

	public function index2(){
		$this->set('tests', $this->Gridtest->find('all',['recursive' => 2]));

		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$this->set(compact('productdetailmasters'));
	}

	public function test(){
		if ($this->request->is('post')) {
			$tyy1 = $this->data['selectym1']['year'];
			$tmm1 = $this->data['selectym1']['month'];
			$tdd1 = $this->data['selectym1']['day'];
			$productdetailmasters_id = $this->data['productdetailmasters_id'];
		}else{
			$tyy1 = date('Y');
			$tmm1 = date('m');
			$tdd1 = date('d');
			$productdetailmasters_id = null;
		}

		$paramsDate = $tyy1.'-'.$tmm1.'-'.$tdd1;
		$week = ['日','月','火','水','木','金','土'];

		if ($productdetailmasters_id == null) {
			$this->set('tests', $this->Gridtest->Select2($tyy1,$tmm1,$tdd1));
		} else {
			$this->set('tests', $this->Gridtest->Select($tyy1,$tmm1,$tdd1,$productdetailmasters_id));
		}

		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$this->set(compact('productdetailmasters','paramsDate','week'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Gridtest->create();
			// 開始日と終了日が同一ではないとき、長期間フラグをONにする
			if($this->request->data['Gridtest']['start_date'] != $this->request->data['Gridtest']['end_date']){
				$this->request->data['Gridtest']['sortflg'] = 1;
				if ($this->Gridtest->save($this->request->data)) {
					$this->Flash->success('保存しました');
					return $this->redirect(array('action' => 'test'));
				}
			} else {
				$this->request->data['Gridtest']['sortflg'] = 0;
				if ($this->Gridtest->save($this->request->data)) {
					$this->Flash->success('保存しました');
					return $this->redirect(array('action' => 'test'));
				} else {
					//$this->Flash->error('失敗しました');
				}
			}
		}
		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$carmasters = $this->Carmaster->SelectList();
		$this->set(compact('productdetailmasters','carmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Gridtest->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Gridtest->id = $id;
			// 開始日と終了日が同一ではないとき、長期間フラグをONにする
			if($this->request->data['Gridtest']['start_date'] != $this->request->data['Gridtest']['end_date']){
				$this->request->data['Gridtest']['sortflg'] = 1;
				if ($this->Gridtest->save($this->request->data)) {
					$this->Flash->success('保存しました');
					return $this->redirect(array('action' => 'test'));
				}
			} else {
				$this->request->data['Gridtest']['sortflg'] = 0;
				if ($this->Gridtest->save($this->request->data)) {
					$this->Flash->success('保存しました。');
					return $this->redirect(array('action' => 'test'));
				}
				//$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$carmasters = $this->Carmaster->SelectList();
		$this->set(compact('productdetailmasters','carmasters'));
	}

	public function delete($id = null) {
		$this->Gridtest->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Gridtest']['delflg'] = 1;
			$this->Gridtest->id = $id;
			if ($this->Gridtest->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));

	}

}
