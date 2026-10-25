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
class HostTruckkindsController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Truckkind', 'Carmaster','Productdetailmaster','Shippermaster','Departmentmaster');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
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
	public function index($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$this->paginate = ['Truckkind' => [
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			    'Shippermaster.shippername',
			    'Shippermaster.shippershortname'
			],
			'limit' => 100,
			'conditions' => [
				'Truckkind.carmasters_id' => $id,
				'OR' => [
					['Truckkind.delflg' => ''],
					['Truckkind.delflg' => 0]
				]
			],
		    'Order'=>[
		        'Truckkind.displayno',
		        'Productdetailmaster.pdname'
		    ],
			'recursive' => 0
		]];
		$this->set('truckkinds', $this->Paginator->paginate('Truckkind'));

		if ($this->request->is('post')) {
			$this->Truckkind->create();
			$this->request->data['Truckkind']['carmasters_id'] = $id;
			if ($this->Truckkind->save($this->request->data)) {
				$this->Flash->success(__('保存しました.'));
				return $this->redirect(['action' => 'index', $id]);
			} else {
				$this->Flash->error(__('保存できません。ご確認ください。'));
			}
		}

		$carmasters = $this->Carmaster->findById($id,['departmentmasters_id'],null,0);
		$did = $carmasters['Carmaster']['departmentmasters_id'];
		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));

		$this->set(compact('carmasters','departmentmasters','shippermasters','productdetailmasters','did'));
	}

	public function edit($kid = null,$tid = null) {
		$post = $this->Truckkind->findById($kid);
		if (!$post) {
			throw new NotFoundException(__('存在しません。'));
		}

		$carmasters = $this->Carmaster->findById($tid,['plateno','departmentmasters_id'],null,0);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$did = $carmasters['Carmaster']['departmentmasters_id'];
		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList2($post['Truckkind']['shippermasters_id']);

		$this->set(compact('carmasters','departmentmasters','shippermasters','productdetailmasters','did'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Truckkind->id = $kid;
			$this->request->data['Truckkind']['carmasters_id'] = $tid;
			if ($this->Truckkind->save($this->request->data)) {
				$this->Flash->success(__('車両の組み合わせを修正しました。'));
				return $this->redirect(['action' => 'index', $tid]);
			} else {
				$this->Flash->error(__('修正に失敗しました。'));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($kid = null, $tid = null) {
		$this->Truckkind->id = $kid;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Truckkind']['delflg'] = 1;
			$this->Truckkind->id = $kid;
			if ($this->Truckkind->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index', $tid));
	}

}
