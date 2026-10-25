<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostLoadingmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Loadingmaster', 'Loadingmasterimage', 'Departmentmaster', 'Branchmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Loadingmaster->presetVars;
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
		$conditions = $this->Loadingmaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'conditions' => ['AND' => $conditions, ['Loadingmaster.delflg' => 0]],
			'limit' => 10,
			'order' => [
				'Loadingmaster.loadingcode' => 'asc',
				'Loadingmaster.id' => 'asc'
			]
		];
		$this->set('loadingmasters', $this->paginate('Loadingmaster'));

		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$branchmasters = $this->Branchmaster->SelectBranchList();
		$this->set(compact('departmentmasters', 'branchmasters'));
	}

	public function add() {
// 		if($this->request->is('post')) {
// 			/** 画像がアップロードされているかどうかの判定
// 			 * 	されていればsaveAll　されていなければsaveで余計なデータをDeliverymasterimageに登録しないようにしている
// 			 */
// 			if ($this->request->data['Loadingmasterimage']['0']['img']['error'] == 0) {
// 				$imgurl = Router::url('/app/webroot/photo/resize/', true).$this->request->data['Loadingmasterimage']['0']['img']['name'];
// 				$this->request->data['Loadingmasterimage']['0']['imgurl'] = $imgurl;
// 				if ($this->Loadingmaster->saveAll($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			} else {
// 				if ($this->Loadingmaster->save($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			}
// 		}

		if ($this->request->is('post')) {
			$i = 0;
			foreach ($this->request->data['Image'] as $image) {
				if ($this->request->data['Image'][$i]['img']['error'] == 0) {
					$this->request->data['Loadingmasterimage'][$i]['img'] = $image['img'];
					$this->request->data['Loadingmasterimage'][$i]['imgcomment'] = $image['imgcomment'];
				}
				$i = $i + 1;
			}
			$this->Loadingmaster->create();
			if ($this->Loadingmaster->saveAll($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				//debug($this->Loadingmaster->validationErrors);
				if (isset($this->Loadingmaster->validationErrors['Loadingmasterimage'])) {
					$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
				} else {
					$this->Flash->error('失敗しました。');
				}
			}
		}

		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$branchmasters = $this->Branchmaster->SelectBranchList();
		$this->set(compact('departmentmasters', 'branchmasters'));
	}

	public function edit($id = null) {
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$branchmasters = $this->Branchmaster->SelectBranchList();
		$this->set(compact('departmentmasters', 'branchmasters'));

		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Loadingmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		$this->set(compact('post'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Loadingmaster->id = $id;
			/**
			 * 複数の画像を登録するために、Imageに一時格納されていた要素をLoadingmasterimageに入れている
			 * このときerrorの内容で画像アップロードがなされているかを判定し、されていない場合は余分なデータを作らないようにしている
			 */
			$i = 0;
			foreach ($this->request->data['Image'] as $image) {
				if ($this->request->data['Image'][$i]['img']['error'] == 0) {
					$this->request->data['Loadingmasterimage'][$i]['img'] = $image['img'];
					$this->request->data['Loadingmasterimage'][$i]['imgcomment'] = $image['imgcomment'];
				}
				$i = $i + 1;
			}
			if ($this->Loadingmaster->saveAll($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
				//debug($this->request->data);
			} else {
				if (isset($this->Loadingmaster->validationErrors['Loadingmasterimage'])) {
					$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
				} else {
					$this->Flash->error('失敗しました。');
				}
			}
			//$this->render();
			//return $this->redirect(['action' => 'index']);
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}


// 		if (!$id) {
// 			throw new NotFoundException(__('Invalid post'));
// 		}

// 		$post = $this->Loadingmaster->findById($id);
// 		if (!$post) {
// 			throw new NotFoundException(__('Invalid post'));
// 		}

// 		if ($this->request->is(array('post', 'put'))) {
// 			$this->Loadingmaster->id = $id;
// 			/** 画像がアップロードされているかどうかの判定
// 			 * 	されていればsaveAll　されていなければsaveで余計なデータをLoadingmasterimageに登録しないようにしている
// 			 */
// 			if ($this->request->data['Loadingmasterimage']['0']['img']['error'] == 0) {
// 				$imgurl = Router::url('/app/webroot/photo/original/', true).$this->request->data['Loadingmasterimage']['0']['img']['name'];
// 				$this->request->data['Loadingmasterimage']['0']['imgurl'] = $imgurl;
// 				$this->request->data['Loadingmasterimage']['0']['imgcomment'] = $this->request->data['Loadingmaster']['imgcomment'];
// 				if ($this->Loadingmaster->saveAll($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 					//debug ($this->request->data);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}
// 			} else {
// 				if ($this->Loadingmaster->save($this->request->data)) {
// 					$this->Flash->success('保存しました。');
// 					return $this->redirect(['action' => 'index']);
// 				} else {
// 					$this->Flash->error('失敗しました。');
// 				}

// 			}
// 		}

// 		if (!$this->request->data) {
// 			$this->request->data = $post;
// 		}

// 		$departmentmasters = $this->Departmentmaster->find('list',[
// 			'fields' => ['id','departmentname']
// 		]);
// 		$branchmasters = $this->Branchmaster->find('list',[
// 			'fields' => ['id','branchname']
// 		]);
// 		$this->set(compact('departmentmasters', 'branchmasters'));
	}

	public function delete($id = null) {
		$this->Loadingmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Loadingmaster']['delflg'] = 1;
			$this->Loadingmaster->id = $id;
			if ($this->Loadingmaster->save($this->request->data, false, ['delflg'])){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function image($id = null) {
		$this->set('loadingimages', $this->Loadingmasterimage->find('all', [
			'conditions' => [
				'Loadingmasterimage.loadingmasters_id' => $id
			]
		]));
		$this->set('loadingname', $this->Loadingmaster->find('first', [
			'fields' => [
				'Loadingmaster.loadingname'
			],
			'conditions' => [
				'Loadingmaster.id' => $id
			]
		]));
	}

	public function imageedit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Loadingmasterimage->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Loadingmasterimage->id = $id;
			if ($this->Loadingmasterimage->save($this->request->data)) {
				$this->Flash->success('変更しました。');
				return $this->redirect(['action' => 'image', $post['Loadingmaster']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function imagedelete($id = null, $did = null) {
		$post = $this->Loadingmasterimage->findById($id);
		$this->Loadingmasterimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$fileName = './loadingimages/original/'.$this->Loadingmasterimage->id.'_'.$post['Loadingmasterimage']['img_file_name'];
			$fileName2 = './loadingimages/resize/'.$this->Loadingmasterimage->id.'_'.$post['Loadingmasterimage']['img_file_name'];
			unlink($fileName);
			unlink($fileName2);
			if ($this->Loadingmasterimage->delete($id)) {
				$this->Flash->success(__($fileName.'を削除しました'));
			} else {
				$this->Flash->error(__('削除に失敗しました'));
			}
		}
		return $this->redirect(['action' => 'image', $did]);
	}

}
