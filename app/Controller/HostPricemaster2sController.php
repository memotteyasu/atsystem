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
class HostPricemaster2sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Pricemaster','Departmentmaster','Shippermaster','Deliverymaster','Loadingmaster','Productdetailmaster','Productcontainer'];
	public $components = ['Search.Prg'];
	public $presetVars = [];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Pricemaster->presetVars;
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
	public function index() {
		// 検索条件設定
		$w_cond1 = '';
		$w_cond2 = '';
		if($this->Session->check('Pricemaster.cond1')){
			$w_cond1 = $this->Session->read('Pricemaster.cond1');
		}
		if($this->Session->check('Pricemaster.cond2')){
			$w_cond2 = $this->Session->read('Pricemaster.cond2');
		}
		$this->Prg->commonProcess();
		if(isset($this->passedArgs['pricename'])){
			$w_cond1 = trim($this->passedArgs['pricename']);
			$this->Session->write('Pricemaster.cond1', $w_cond1);
		}
		if(isset($this->passedArgs['pricename2'])){
			$w_cond2 = trim($this->passedArgs['pricename2']);
			$this->Session->write('Pricemaster.cond2', $w_cond2);
		}
		if(empty($this->passedArgs)){
			$this->Session->delete('Pricemaster.cond1');
			$this->Session->delete('Pricemaster.cond2');
		}
		$this->request->data['Pricemaster']['pricename'] = $w_cond1;
		$this->request->data['Pricemaster']['pricename2'] = $w_cond2;
		$queryOptions = [
			'conditions' => [
				'Pricemaster.keihiflg' => 0,
				'Pricemaster.delflg' => 0,
				'Pricemaster.shippermasters_id' => [42, 80]
			],
			'fields' => [
				'Pricemaster.id',
				'Pricemaster.pricename',
				'Pricemaster.pricecomment',
				'Pricemaster.price',
				'Pricemaster.keihiflg',
				'Pricemaster.updateflg',
				'Departmentmaster.departmentname',
				'Shippermaster.shippershortname',
			],
			'order' => [
				'Pricemaster.id' => 'desc'
			],
			'recursive' => 0
		];
		if(!empty($w_cond1)){
			$queryOptions['conditions'][] = ['Pricemaster.pricename LIKE' => '%'.$w_cond1.'%'];
		}
		if(!empty($w_cond2)){
			$queryOptions['conditions'][] = ['Pricemaster.pricename LIKE' => '%'.$w_cond2.'%'];
		}
		if(empty($w_cond1) && empty($w_cond2)){
			$queryOptions['limit'] = 20;
		}
		$pricemasters = $this->Pricemaster->find('all', $queryOptions);
		$queryLog = $this->Pricemaster->getDataSource()->getLog(false, false);
		$queryEntries = !empty($queryLog['log']) ? $queryLog['log'] : [];
		$lastQuery = !empty($queryEntries) ? end($queryEntries) : [];
		$searchSql = isset($lastQuery['query']) ? $lastQuery['query'] : '';

		$this->set(compact('pricemasters', 'searchSql'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}
	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Pricemaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}
		if ($this->request->is(array('post', 'put'))) {

			$this->Pricemaster->id = $id;
			if ($this->Pricemaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				$redirectParams = ['action' => 'index'];
				if ($this->Session->check('Pricemaster.cond1')) {
					$redirectParams['pricename'] = $this->Session->read('Pricemaster.cond1');
				}
				if ($this->Session->check('Pricemaster.cond2')) {
					$redirectParams['pricename2'] = $this->Session->read('Pricemaster.cond2');
				}
				return $this->redirect($redirectParams);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function delete($id = null) {
		$this->Pricemaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Pricemaster']['delflg'] = 1;
			if ($this->Pricemaster->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}

}
