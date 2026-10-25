<?php
App::uses('AppController', 'Controller');
/**
 * Host2Deliverymaster Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host2DeliverymastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Deliverymaster','Deliverymasterimage','Departmentmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Deliverymaster->presetVars;
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
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Deliverymaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
				'Deliverymaster.id',
				'Deliverymaster.deliverycode',
				'Deliverymaster.deliveryname',
				'Deliverymaster.deliveryshortname',
			],
			'conditions' => ['AND' => $conditions, ['Deliverymaster.delflg' => 0]],
			'limit' => 10,
			'order' => [
				'Deliverymaster.deliverycode' => 'asc',
				'Deliverymaster.id' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('deliverymasters', $this->paginate('Deliverymaster'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function add($copykey = null) {
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
	    $this->set(compact('departmentmasters'));

	    if ($this->request->is(array('post', 'put'))) {
	        	$this->Deliverymaster->create();
	        	$this->Deliverymaster->areamasters_id = 0; //強制的に「なし」をセット
        	    	if ($this->Deliverymaster->save($this->request->data)) {
        	    		$this->Flash->success('納入先を登録しました。');
        	    		return $this->redirect(['action' => 'index']);
        	    	} else {
        	    		$this->Flash->error('失敗しました。');
        	    	}
	    }else{
	        if(isset($copykey)){
	            // $copykeyが存在する場合、idを読み、内容をコピー
	            $deliverymasters = $this->Deliverymaster->findById($copykey);
	            $this->request->data = $deliverymasters;
	        }
	    }
	}

	public function edit($id = null) {
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));

		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Deliverymaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		$this->set(compact('post'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Deliverymaster->id = $id;
			if ($this->Deliverymaster->save($this->request->data)) {
				$this->Flash->success('納入先の編集に成功しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('編集に失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null) {
		$this->Deliverymaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Deliverymaster']['delflg'] = 1;
			$this->Deliverymaster->id = $id;
			if ($this->Deliverymaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function image($id = null) {
		$this->set('deliveryimages', $this->Deliverymasterimage->find('all', [
			'conditions' => [
				'Deliverymasterimage.deliverymasters_id' => $id
			]
		]));
		$this->set('deliveryname', $this->Deliverymaster->find('first', [
			'fields' => [
				'Deliverymaster.deliveryname'
			],
			'conditions' => [
				'Deliverymaster.id' => $id
			]
		]));
	}


	public function imagedelete($id = null, $did = null) {
		$post = $this->Deliverymasterimage->findById($id);
		$this->Deliverymasterimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$fileName = './deliveryimages/original/'.$this->Deliverymasterimage->id.'_'.$post['Deliverymasterimage']['img_file_name'];
			$fileName2 = './deliveryimages/resize/'.$this->Deliverymasterimage->id.'_'.$post['Deliverymasterimage']['img_file_name'];
			unlink($fileName);
			unlink($fileName2);
			if ($this->Deliverymasterimage->delete($id)) {
				$this->Flash->success(__($fileName.'を削除しました'));
			} else {
				$this->Flash->error(__('削除に失敗しました'));
			}
		}
		return $this->redirect(['action' => 'image', $did]);
	}

}
