<?php
App::uses('AppController', 'Controller');
/**
 * HostWorkplace Controller
 */
class HostWorkplacesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Workplace','Workplaceimage','Departmentmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Workplace->presetVars;
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
	    $page='HostWorkplaces';

		if(($this->request->is(['patch', 'post', 'put']))){
		    $backdatas = ['did' => $this->request->data['Workplace']['departmentmasters_id'], 'Page' => $page];
		    $this->Session->write('BackData1', $backdatas);
		    $did = $this->Session->read('BackData1.did');
		}else{
		    $did = $this->Session->read('BackData1.did');
		}

		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Workplace->parseCriteria($this->passedArgs);

		$conditions['Workplace.departmentmasters_id'] = $did;
		$this->request->data['Workplace']['departmentmasters_id']=$did;

		$this->paginate = [
			'fields' => [
				'Workplace.id',
				'Workplace.workplacename',
				'Workplace.workplaceshortname',
			    'Workplace.placetel'
			],
			'conditions' => [
			    'AND' => $conditions,
			    [
    			    'Workplace.delflg' => 0,
    			    'Workplace.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
			    ]
			],
			'limit' => 10,
			'order' => [
				'Workplace.workplacename' => 'asc',
			],
			'recursive' => 0,
		];
		$this->set('workplaces', $this->paginate('Workplace'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function add() {
	    $this->edit();
	    $this->render('edit');
	}
	public function edit($id = null) {
	    $departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set('departmentmasters',$departmentmasters);

	    if ($this->request->is(array('post', 'put'))) {
	        $this->request->data['Workplace']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        if ($this->Workplace->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				if (isset($this->request->named['page'])) {
					return $this->redirect(['controller' => $this->request->named['page'], 'action' => 'index']);
				} else {
					return $this->redirect(['action' => 'index']);
				}
	        } else {
	            $this->Flash->error(__('保存できません'));
	        }
	    } else {
	        if($id){
	            $options = array('conditions' => array('Workplace.' . $this->Workplace->primaryKey => $id));
	            $this->request->data = $this->Workplace->find('first', $options);
	        }
	    }
	}


	public function delete($id = null) {
		$this->Workplace->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Workplace']['delflg'] = 1;
			$this->Workplace->id = $id;
			if ($this->Workplace->save($this->request->data,false,array('delflg'))){
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
