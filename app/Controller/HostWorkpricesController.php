<?php
App::uses('AppController', 'Controller');
/**
 * HostWorkprices Controller
 */
class HostWorkpricesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Workprice','Departmentmaster','Branchmaster'];
	public $components = ['Search.Prg'];
	public $presetVars = [];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Workprice->presetVars;
		$this->page = 'HostWorkprices';
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
			$this->set('user',$user);
			return true;
		}
		return false;
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
//		$conditions = $this->Workprice->parseCriteria($this->passedArgs);
        $conditions=[];
		if ($this->request->data) {
		    $conditions = $this->Workprice->parseCriteria($this->passedArgs);
		    $this->Session->write('BackData1.conditions',$conditions);
		}else{
		    if($this->Session->read('BackData1.page')=='HostWorkprices'){
		        $conditions = $this->Session->read('BackData1.conditions');
		    }else{
		        $this->Session->delete('BackData1');
		        $backdata1 = [
		            'page'=>$this->page,
		            'conditions'=>[
		                'Workprice.branchmasters_id'=>0,
		                'Workprice.departmentmasters_id'=>0,
		                'Workprice.roudouflg'=>0,
		                'Workprice.buairank'=>0
		            ]
		        ];
		        $this->Session->write('BackData1',$backdata1);
		        $conditions = $backdata1['conditions'];
		    }
		}

		$this->paginate = [
			'fields' => [
				'Workprice.id',
				'Workprice.startkilo',
				'Workprice.endkilo',
				'Workprice.price',
			    'Workprice.pricecomment',
			    'Workprice.zangyojikan',
			    'Workprice.shinyajikan',
			    'Workprice.zangyotanka',
			    'Workprice.shinyatanka',
			    'Workprice.untinbuai',
				'Departmentmaster.departmentname',
				'Branchmaster.branchname'
			],
			'conditions' => [
				'AND' => $conditions,
			    'Workprice.delflg'=>0,
				'Workprice.kaisyas_id' => $this->kaisyas_id
			],
			'limit' => 10,
			'order' => [
				'Workprice.departmentmasters_id' => 'asc',
				'Workprice.branchmasters_id' => 'asc',
				'Workprice.startkilo' => 'asc',
			],
			'recursive' => 0,
		];
		$this->set('workprices', $this->paginate('Workprice'));

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
		$roudouflgs = ['1'=>'正社員','2'=>'嘱託','3'=>'パート','4'=>'季節','5'=>'バイト','9'=>'協力会社'];
		$this->set('roudouflgs',$roudouflgs);
		$buairanks = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
		$this->set('buairanks',$buairanks);

	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Workprice->create();
			$this->request->data['Workprice']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Workprice->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}
		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
		$roudouflgs = ['1'=>'正社員','2'=>'嘱託','3'=>'パート','4'=>'季節','5'=>'バイト','9'=>'協力会社'];
		$this->set('roudouflgs',$roudouflgs);
		$buairanks = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
		$this->set('buairanks',$buairanks);
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Workprice->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Workprice->id = $id;
			if ($this->Workprice->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
		$roudouflgs = ['1'=>'正社員','2'=>'嘱託','3'=>'パート','4'=>'季節','5'=>'バイト','9'=>'協力会社'];
		$this->set('roudouflgs',$roudouflgs);
		$buairanks = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
		$this->set('buairanks',$buairanks);
	}

	public function delete($id = null) {
		$this->Workprice->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Workprice']['delflg'] = 1;
			if ($this->Workprice->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}


}
