<?php
App::uses('AppController', 'Controller');
/**
 * HostWorkplaceimages Controller
 *
 */
class HostWorkplaceimagesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Workplace','Workplaceimage');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
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
		$this->set('workplaceimages', $this->Workplaceimage->find('all', [
			'conditions' => [
				'AND' => [
					'Workplaceimage.workplaces_id' => $id,
					'Workplaceimage.delflg' => '0',
				]
			]
		]));
		$this->set('workplacename', $this->Workplace->find('first', [
			'fields' => [
				'Workplace.workplacename'
			],
			'conditions' => [
				'Workplace.id' => $id
			]
		]));
		$this->set('workplaceid',$id);
	}

	public function add($id = null) {
		$workplaces = $this->Workplace->findById($id,null,null,-1);
		$this->set('workplaces',$workplaces);

		if ($this->request->is('post')) {
			if ($this->request->data['Workplaceimage']['img']['error'] == 0) {
				$this->request->data['Workplaceimage']['workplaces_id'] = $id;
				$this->Workplaceimage->create();
				if ($this->Workplaceimage->saveAll($this->request->data)) {
					$this->Flash->success('画像を保存しました。');
					return $this->redirect(['action' => 'index', $id]);
				} else {
					$this->Flash->error('画像保存に失敗しました。');
				}
			} else {
				$this->Flash->error('画像が選択されていません。');
			}
		}
	}

	public function edit($id = null) {
	    if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Workplaceimage->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
		    $this->Workplaceimage->id = $id;
		    if ($this->Workplaceimage->save($this->request->data)) {
				$this->Flash->success('変更しました。');
				return $this->redirect(['action' => 'index', $post['Workplace']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null, $id2 = null) {
	    $this->Workplaceimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Workplaceimage']['delflg'] = 1;
			$this->Workplaceimage->id = $id;
			if ($this->Workplaceimage->save($this->request->data, false, ['delflg'])){
				$this->Flash->success(__('画像を削除しました。'));
			}else{
				$this->Flash->error(__('画像削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index', $id2]);
	}

	public function imagedelete($id = null, $did = null) {
	    $post = $this->Workplaceimage->findById($id);
	    $this->Workplaceimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
		    $fileName = './Workplaceimages/original/'.$this->Workplaceimage->id.'_'.$post['Workplaceimage']['img_file_name'];
		    $fileName2 = './Workplaceimages/resize/'.$this->Workplaceimage->id.'_'.$post['Workplaceimage']['img_file_name'];
			unlink($fileName);
			unlink($fileName2);
			if ($this->Workplaceimage->delete($id)) {
				$this->Flash->success(__($fileName.'を削除しました'));
			} else {
				$this->Flash->error(__('削除に失敗しました'));
			}
		}
		return $this->redirect(['action' => 'image', $did]);
	}

}
