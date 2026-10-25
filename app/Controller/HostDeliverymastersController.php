<?php
App::uses('AppController', 'Controller');
/**
 * HostDeliverymaster Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostDeliverymastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Deliverymaster','Deliverymasterimage','Departmentmaster','Areamaster');
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
			'conditions' => ['AND' => $conditions, ['Deliverymaster.delflg' => 0]],
			'limit' => 10,
			'order' => [
				'Deliverymaster.deliverycode' => 'asc',
				'Deliverymaster.id' => 'asc'
			]
		];
		$this->set('deliverymasters', $this->paginate('Deliverymaster'));

		$departmentmasters = $this->Departmentmaster->SelectList();
		$areamasters = $this->Areamaster->SelectList();
		$this->set(compact('areamasters','departmentmasters'));
	}

	public function add() {
	    $departmentmasters = $this->Departmentmaster->SelectList();
	    $areamasters = $this->Areamaster->SelectList();
	    $this->set(compact('areamasters','departmentmasters'));

	    if ($this->request->is('post')) {
	    	$i = 0;
	    	foreach ($this->request->data['Image'] as $image) {
	    		if ($this->request->data['Image'][$i]['img']['error'] == 0) {
	    			$this->request->data['Deliverymasterimage'][$i]['img'] = $image['img'];
	    			$this->request->data['Deliverymasterimage'][$i]['imgcomment'] = $image['imgcomment'];
	    		}
	    		$i = $i + 1;
	    	}
	    	$this->Deliverymaster->create();
	    	if ($this->Deliverymaster->saveAll($this->request->data)) {
	    		$this->Flash->success('保存しました。');
	    		return $this->redirect(['action' => 'index']);
	    	} else {
	    		if (isset($this->Deliverymaster->validationErrors['Deliverymasterimage'])) {
	    			$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
	    		} else {
	    			$this->Flash->error('失敗しました。');
	    		}
	    		//$this->Flash->error('失敗しました。');
	    	}
	    }

// 	    if($this->request->is('post')) {
// 			/** 画像がアップロードされているかどうかの判定
// 			 * 	されていればsaveAll　されていなければsaveで余計なデータをDeliverymasterimageに登録しないようにしている
// 			 */
// /* 一旦アウト
// 		    if ($this->request->data['Deliverymasterimage']['0']['img']['error'] == 0) {
// 				$imgurl = Router::url('/photo/resize/', true).$this->request->data['Deliverymasterimage']['0']['img']['name'];
// 				$this->request->data['Deliverymasterimage']['0']['imgurl'] = $imgurl;
// 				if ($this->Deliverymaster->saveAll($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			} else {
// 				if ($this->Deliverymaster->save($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			}

// 2019-03-23 テスト */

// 		    if ($this->request->data['Deliverymasterimage']['img']['error'] == 0) {
// 		        $imgurl = Router::url('/photo/resize/', true).$this->request->data['Deliverymasterimage']['img']['name'];
// 		        $this->request->data['Deliverymasterimage']['imgurl'] = $imgurl;
// 		        if ($this->Deliverymaster->saveAll($this->request->data)) {
// 		            $this->Flash->success('保存しました。');
// 		            // return $this->redirect(['action' => 'index']);
// 		            return;
// 		        } else {
// 		            $this->Flash->error('失敗しました。');
// 		        }
// 		    } else {
// 		        if ($this->Deliverymaster->save($this->request->data)) {
// 		            $this->Flash->success('保存しました。');
// 		            //return $this->redirect(['action' => 'index']);
// 		            return;
// 		        } else {
// 		            $this->Flash->error('失敗しました。');
// 		        }
// 		    }
// 		    debug($this->request->data);
// 		}
	}

	public function edit($id = null) {
		$departmentmasters = $this->Departmentmaster->SelectList();
		$areamasters = $this->Areamaster->SelectList();
		$this->set(compact('areamasters','departmentmasters'));

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
			/**
			 * 複数の画像を登録するために、Imageに一時格納されていた要素をDeliverymasteriamgeに入れている
			 * このときerrorの内容で画像アップロードがなされているかを判定し、されていない場合は余分なデータを作らないようにしている
			 */
			$i = 0;
			foreach ($this->request->data['Image'] as $image) {
				if ($this->request->data['Image'][$i]['img']['error'] == 0) {
					$this->request->data['Deliverymasterimage'][$i]['img'] = $image['img'];
					$this->request->data['Deliverymasterimage'][$i]['imgcomment'] = $image['imgcomment'];
				}
				$i = $i + 1;
			}
			if ($this->Deliverymaster->saveAll($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				if (isset($this->Deliverymaster->validationErrors['Deliverymasterimage'])) {
					$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
				} else {
					$this->Flash->error('失敗しました。');
				}
				//$this->Flash->error('失敗しました。');
			}
			//$this->render();

// 			if ($this->request->data['Image']['0']['img']['error'] == 0) {
// 				//$imgurl = Router::url('/app/webroot/deliveryimages/original/', true).$this->request->data['Deliverymasterimage']['0']['img']['name'];
// 				//$this->request->data['Deliverymasterimage']['0']['imgurl'] = $imgurl;
// 				//$imgurl = $this->request->data['Deliverymasterimage']['0']['img']['name'];
// 				$i = 0;
// 				foreach ($this->request->data['Image'] as $image) {
// 					$this->request->data['Deliverymasterimage'][$i]['img'] = $image['img'];
// 					$this->request->data['Deliverymasterimage'][$i]['imgcomment'] = $image['imgcomment'];
// 					$i = $i + 1;
// 				}
// 				if ($this->Deliverymaster->saveAll($this->request->data)) {
// 					/** 画像をアップロード後にリネームされた画像に繋がるようにimgurlの更新を行っている
// 					 *	画像のリネームがid_元ファイル名.拡張子のため
// 					 */
// 					//$id = $this->Deliverymasterimage->getLastInsertID();
// 					//$imgurl = Router::url('/app/webroot/deliveryimages/original/', true).$id.'_'.$imgurl;
// 					//$this->Deliverymasterimage->saveField('imgurl', $imgurl);

// 					$this->Flash->success('保存しました。');
// 					//return $this->redirect(['action' => 'index']);
// 					debug($this->request->data);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			} else {
// 				if ($this->Deliverymaster->save($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					//return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			}
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

	public function imageedit($id = null) {
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
				return $this->redirect(['action' => 'image', $post['Deliverymaster']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
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
