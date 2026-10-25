<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host2LoadingmasterimagesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Loadingmaster', 'Loadingmasterimage');
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
		$this->set('loadingmasterimages', $this->Loadingmasterimage->find('all', [
			'conditions' => [
				'AND' => [
					'Loadingmasterimage.loadingmasters_id' => $id,
					'OR' => [
						'Loadingmasterimage.delflg' => '',
						'Loadingmasterimage.delflg' => 0,
					]
				]
			],
		]));

		$this->set('loadingname', $this->Loadingmaster->find('first',[
			'fields' => [
				'Loadingmaster.loadingname'
			],
			'conditions' => [
				'Loadingmaster.id' => $id,
			],
		]));

		$this->set('loadingmasterid', $id);
	}

	public function add($id = null) {
		$loadingmasters = $this->Loadingmaster->findById($id,null,null,-1);
		$this->set('loadingmasters',$loadingmasters);

		if ($this->request->is('post')) {
			if ($this->request->data['Loadingmasterimage']['img']['error'] == 0) {
				$this->request->data['Loadingmasterimage']['loadingmasters_id'] = $id;
				$this->Loadingmasterimage->create();
				if ($this->Loadingmasterimage->saveAll($this->request->data)) {
					$this->Flash->success('保存しました。');
					//debug($this->request->data);
					return $this->redirect(['action' => 'index', $id]);
				} else {
					$this->Flash->error('画像保存に失敗しました。');
	// 				if (isset($this->Loadingmasterimage->validationErrors['Loadingmasterimage'])) {
	// 					$this->Flash->error('画像ファイルはjpgかgif形式でアップロードしてください。');
	// 				} else {
	// 					$this->Flash->error('画像保存に失敗しました。');
	// 				}
				}
			} else {
				$this->Flash->error('画像が選択されていません。');
				//debug($this->request->data);
			}
		}
	}

	public function edit($id = null) {
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
				return $this->redirect(['action' => 'index', $post['Loadingmaster']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null, $id2 = null) {
		$this->Loadingmasterimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Loadingmasterimage']['delflg'] = 1;
			$this->Loadingmasterimage->id = $id;
			if ($this->Loadingmasterimage->save($this->request->data, false, ['delflg'])){
				$this->Flash->success(__('画像を削除しました。'));
			}else{
				$this->Flash->error(__('画像削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index', $id2]);
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
