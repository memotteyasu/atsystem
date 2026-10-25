<?php
App::uses('AppController', 'Controller');
/**
 * Host2Deliverymasterimages Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host2DeliverymasterimagesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Deliverymaster','Deliverymasterimage');
    public $components = array('Search.Prg');
    public $presetVars = array();

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
		$this->set('deliverymasterimages', $this->Deliverymasterimage->find('all', [
			'conditions' => [
				'AND' => [
					'Deliverymasterimage.deliverymasters_id' => $id,
					'OR' => [
						'Deliverymasterimage.delflg' => '',
						'Deliverymasterimage.delflg' => 0,
					]
				]
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
		$this->set('deliverymasterid',$id);
	}

	public function add($id = null) {
		$deliverymasters = $this->Deliverymaster->findById($id,null,null,-1);
		$this->set('deliverymasters',$deliverymasters);

		if ($this->request->is('post')) {
			if ($this->request->data['Deliverymasterimage']['img']['error'] == 0) {
				$this->request->data['Deliverymasterimage']['deliverymasters_id'] = $id;
				$this->Deliverymasterimage->create();
				if ($this->Deliverymasterimage->saveAll($this->request->data)) {
					$this->Flash->success('画像を保存しました。');
					return $this->redirect(['action' => 'index', $id]);
				} else {
					$this->Flash->error('画像保存に失敗しました。');
				}
			} else {
				$this->Flash->error('画像が選択されていません。');
			}
		}

// 	    $deliverymasters = $this->Deliverymaster->findById($id);
// 	    $this->set('deliverymasters',$deliverymasters);

// 	    if ($this->request->is('post')) {
// 	        $this->request->data['Deliverymasterimage']['deliverymasters_id'] = $id;
// 	        $this->Deliverymasterimage->create();
//         	    	if ($this->Deliverymasterimage->saveAll($this->request->data)) {
//         	    		$this->Flash->success('保存しました。');
//         	    		return $this->redirect(['action' => 'index', $id]);
//         	    	} else {
//         	    		if (isset($this->Deliverymasterimage->validationErrors['Deliverymasterimage'])) {
//         	    			$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
//         	    		} else {
//         	    			$this->Flash->error('失敗しました。');
//         	    		}
//         	    	}
// 	    }
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Deliverymasterimage->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Deliverymasterimage->id = $id;
			if ($this->Deliverymasterimage->save($this->request->data)) {
				$this->Flash->success('変更しました。');
				return $this->redirect(['action' => 'index', $post['Deliverymaster']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null, $id2 = null) {
		$this->Deliverymasterimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Deliverymasterimage']['delflg'] = 1;
			$this->Deliverymasterimage->id = $id;
			if ($this->Deliverymasterimage->save($this->request->data, false, ['delflg'])){
				$this->Flash->success(__('画像を削除しました。'));
			}else{
				$this->Flash->error(__('画像削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index', $id2]);
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
