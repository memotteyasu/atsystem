<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Branchmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Branchmaster';
	public $useTable = 'branchmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'branchname', 'type' => 'like', 'field' => 'Branchmaster.branchname')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'branchname', 'type' => 'value')
	);

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 部署セレクトリスト
	 */
	public function SelectBranchList($kid){
		return $this->find('list', [
			'fields' => ['id','branchname'],
			//'conditions' => ['delflg' => 0],
			'order' => ['id' => 'asc'],
		    'conditions'=>[
		        'kaisyas_id'=>$kid
		    ]
		]);
	}

	public function BranchList($kaisyas_id) {
		return $this->find('list',[
			'fields' => ['id','branchname'],
			'conditions' => [
				'kaisyas_id' => $kaisyas_id,
				//'delflg' => 0
			],
			'order' => ['id' => 'asc']
		]);
	}

	public function BranchList_kintai($kaisyas_id) {
	    return $this->find('list',[
	        'fields' => ['id','branchname'],
	        'conditions' => [
	            'kaisyas_id' => $kaisyas_id,
	            'id <' => 5
	        ],
	        'order' => ['id' => 'asc']
	    ]);
	}
	public function BranchList2($kaisyas_id,$branchmasters_id) {
		return $this->find('list', [
			'fields' => ['id','branchname'],
			'conditions' => [
			/*	'kaisyas_id' => $kaisyas_id, */
				'id' => $branchmasters_id,
			],
			'order' => ['id' => 'asc']
		]);
	}
	public function BranchList3($kaisyas_id,$branchgroups_id) {
		return $this->find('all', [
			'fields' => ['id','branchname'],
			'conditions' => [
			/*	'kaisyas_id' => $kaisyas_id, */
				'branchgroups_id' => $branchgroups_id,
			],
			'order' => ['id' => 'asc'],
			
		]);
	}
	public function BranchList4($kaisyas_id,$rolemasters_id) {
		return $this->find('all', [
			'fields' => ['id','branchname'],
			'conditions' => [
				'kaisyas_id' => $kaisyas_id
			],
			'order' => ['id' => 'asc']
		]);
	}
	//関東用
	public function BranchList_kanto($kaisyas_id,$branchmasters_id) {
		return $this->find('list', [
			'fields' => ['id','branchname'],
			'conditions' => [
				'kaisyas_id' => $kaisyas_id,
				'id' => [3,4]
			],
			'order' => ['id' => 'asc']
		]);
	}
//2022-08-12 add
	public function Branchgrouplist($kaisyas_id,$branchgroups_id) {
		return $this->find('list', [
			'fields' => ['id'],
			'conditions' => [
				'kaisyas_id' => $kaisyas_id,
				'branchgroups_id' => $branchgroups_id,
			],
			'order' => ['id' => 'asc']
		]);
	}
	
/**
 * Validation rules
 *
 * @var array
 */


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Kaisya' => array(
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
