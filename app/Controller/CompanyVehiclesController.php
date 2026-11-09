<?php
App::uses('AppController', 'Controller');

class CompanyVehiclesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html', 'Form');
	public $uses = array('CompanyVehicle', 'Branchmaster');

	public function isAuthorized($user) {
		if (isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 10 && $user['rolemasters_id'] <= 29) {
			if ($user['rolemasters_id'] >= 20) {
				$this->layout = 'layout_clerk';
			}
			$this->set('user', $user);
			return true;
		}
		return false;
	}

	public function index() {
		$companyVehicles = $this->CompanyVehicle->find('all', array(
			'conditions' => array('CompanyVehicle.kaisyas_id' => $this->Auth->user('kaisyas_id'), 'CompanyVehicle.delflg' => 0),
			'order' => array('CompanyVehicle.displayno' => 'asc', 'CompanyVehicle.vehiclename' => 'asc'),
			'recursive' => 0
		));
		$this->set(compact('companyVehicles'));
	}

	public function add() {
		$this->edit();
		$this->render('edit');
	}

	public function edit($id = null) {
		if ($id) {
			$companyVehicle = $this->_companyVehicle($id);
			if (!$companyVehicle) {
				throw new NotFoundException('営業車が見つかりません。');
			}
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['CompanyVehicle']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			if ($id) {
				$this->CompanyVehicle->id = $id;
			} else {
				$this->CompanyVehicle->create();
			}
			if ($this->CompanyVehicle->save($this->request->data)) {
				$this->Flash->success('営業車マスタを保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error('営業車マスタを保存できませんでした。入力内容を確認してください。');
		} elseif ($id) {
			$this->request->data = $companyVehicle;
		}
		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$this->set(compact('branchmasters'));
	}

	public function delete($id = null) {
		$this->request->allowMethod('post', 'delete');
		$companyVehicle = $this->_companyVehicle($id);
		if ($companyVehicle) {
			$this->CompanyVehicle->id = $id;
			$this->CompanyVehicle->saveField('delflg', 1);
			$this->Flash->success('営業車を利用停止にしました。');
		}
		return $this->redirect(array('action' => 'index'));
	}

	protected function _companyVehicle($id) {
		return $this->CompanyVehicle->find('first', array(
			'conditions' => array('CompanyVehicle.id' => $id, 'CompanyVehicle.kaisyas_id' => $this->Auth->user('kaisyas_id'), 'CompanyVehicle.delflg' => 0),
			'recursive' => -1
		));
	}
}
