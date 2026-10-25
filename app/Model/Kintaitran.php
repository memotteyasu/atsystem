<?php
App::uses('AppModel', 'Model');
/**
 * Kintaitran Model
 *
 * @property Users $Users
 */
class Kintaitran extends AppModel {
	public $name = 'Kintaitran';
	public $useTable = 'kintaitrans';
	public $actsAs = array('Containable');

	public function beforeSave($option = []) {
		// These legacy columns remain NOT NULL but are not entered on Host002.
		// Supply a zero duration when the form does not submit a value.
		foreach (['soukoutime', 'sagyoutime'] as $field) {
			if (!isset($this->data[$this->alias][$field]) || $this->data[$this->alias][$field] === '') {
				$this->data[$this->alias][$field] = '00:00:00';
			}
		}

		// MySQL strict mode cannot store an empty string in the tachograph
		// odometer integer columns. Empty optional inputs mean zero kilometres.
		foreach (['tako1_km_s', 'tako1_km_e', 'tako2_km_s', 'tako2_km_e', 'tako3_km_s', 'tako3_km_e'] as $field) {
			if (array_key_exists($field, $this->data[$this->alias]) && $this->data[$this->alias][$field] === '') {
				$this->data[$this->alias][$field] = 0;
			}
		}

		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 休日状態の社員の抽出
	 */
	public function HolidayDriver($paramsDate,$paramsDate2){

		return $this->find('all', [
			'conditions' => [
				'Kintaitran.kintaidate >=' => $paramsDate,
				'Kintaitran.kintaidate <=' => $paramsDate2,
				'Kintaitran.flgmasters_id >=' => 13,
				'Kintaitran.flgmasters_id <=' => 25,					
				'User.rolemasters_id >=' => 30,
				'User.rolemasters_id <=' => 39,
				'Kintaitran.delflg' => 0
			],
			'fields' => [
				'Kintaitran.kintaidate',
				'Kintaitran.users_id',
				'Kintaitran.flgmasters_id',
				'User.usernameK',
				'Flgmaster.flgname',
				'Branchmaster.branchname'
			],
			'joins'=>[
				[
					'table'=>'branchmasters',
					'alias'=>'Branchmaster',
					'type'=>'LEFT',
					'conditions'=>[
						'User.branchmasters_id = Branchmaster.id'
					]
				]
			],
			'order' => [
				'Kintaitran.kintaidate' => 'asc'
			]
		]);
	}
	/* Co003にて部署ごとに休みを出すようにする。bgidにはbranchmaster_idの配列が入る	*/
	public function HolidayDriver_branchgroup($paramsDate,$paramsDate2,$bgid){

		return $this->find('all', [
			'conditions' => [
				'AND' => [
				    'Kintaitran.kintaidate >=' => $paramsDate,
				    'Kintaitran.kintaidate <=' => $paramsDate2,
					'Kintaitran.flgmasters_id >=' => 13,
					'Kintaitran.flgmasters_id <=' => 25,					
					'User.rolemasters_id >=' => 30,
					'User.rolemasters_id <=' => 39,
					'User.branchmasters_id' => $bgid,
					'Kintaitran.delflg' => 0
				]
			],
			'fields' => [
				'Kintaitran.kintaidate',
				'Kintaitran.users_id',
				'Kintaitran.flgmasters_id',
				'User.usernameK',
				'Flgmaster.flgname'
			],
			'order' => [
				'Kintaitran.kintaidate' => 'asc'
			]
		]);
	}
	//13は有給
	public function HolidayDriverList($paramsDate,$paramsDate2){
		$this->virtualFields = ['nameandkaisya' => 'concat(User.usernameK ,"/", Branchmaster.branchname)'];

		return $this->find('list', [
			'conditions' => [
				'Kintaitran.kintaidate >=' => $paramsDate,
				'Kintaitran.kintaidate <=' => $paramsDate2,
				'Kintaitran.flgmasters_id >=' => 13,
				'Kintaitran.flgmasters_id <=' => 25,					
				'User.rolemasters_id >=' => 30,
				'User.rolemasters_id <=' => 39,
				'Kintaitran.delflg' => 0,
			],
			'fields' => [
				'Kintaitran.users_id',
				'nameandkaisya'
			],
			'joins'=>[
				[
					'table'=>'branchmasters',
					'alias'=>'Branchmaster',
					'type'=>'LEFT',
					'conditions'=>[
						'User.branchmasters_id = Branchmaster.id'
					]
				]
			],
			'order' => [
				'Kintaitran.kintaidate' => 'asc'
			],
			'recursive' => 1
		]);
	}

	public function SelectKintaitranHost013s($firstDate,$paramsDate,$branchconditions,$kaisyaid) {
		$this->virtualFields = [
			'sum' => 'sum(Kintaitran.tako_gokei_km)',
			'latestdate' => 'max(Kintaitran.kintaidate)'
		];
		return $this->find('all', [
			'fields' => [
				'Kintaitran.kintaidate',
				'Kintaitran.tako_gokei_km',
				'Kintaitran.users_id',
				// 'User.usernameK',
				'Kintaitran.sum',
				'Kintaitran.latestdate'
			],
			'conditions' => [
				'User.branchmasters_id' => $branchconditions,
				'User.zaisekiflg' => 0,
				'User.kaisyas_id' => $kaisyaid,
				'User.rolemasters_id >=' => 30,
				'User.rolemasters_id <=' => 39,
				'Kintaitran.kintaidate >= ' => $firstDate,
				'Kintaitran.kintaidate <= ' => $paramsDate,
			],
			'order'=>[
				'Kintaitran.kintaidate' => 'desc'
			],
			'group' => [
				'Kintaitran.users_id'
			]
		]);
	}

	public function SelectKintaitranHost014s($firstDate,$paramsDate,$branchconditions,$departmentconditions,$kaisyaid) {
		$this->virtualFields = [
			'sum' => 'sum(Kintaitran.tako_gokei_km)',
		];
		return $this->find('all', [
			'fields' => [
				'User.usernameK',
				'Kintaitran.sum',
			],
			'conditions' => [
			    'User.branchmasters_id' => $branchconditions,
			    'User.departmentmasters_id' => $departmentconditions,
			    'User.zaisekiflg' => 0,
				'User.kaisyas_id' => $kaisyaid,
				'User.rolemasters_id >=' => 30,
				'User.rolemasters_id <=' => 39,
				'Kintaitran.kintaidate >= ' => $firstDate,
				'Kintaitran.kintaidate <= ' => $paramsDate,
			],
			'order'=>[
				'sum' => 'desc'
			],
			'group' => [
				'Kintaitran.users_id'
			]
		]);
	}
/* 出勤、待機、教育、検診、遅早のカウント */
	public function SelectKintaitranHostS20s($firstDate,$paramsDate,$branchconditions,$departmentconditions,$kaisyaid) {
		$this->virtualFields = [
			'flgcount' => 'count(Kintaitran.flgmasters_id)',
		];
		return $this->find('all', [
			'fields' => [
				'User.usernameK',
				'Kintaitran.flgcount',
				'User.yusyu',
				'User.nenpi',
				'User.katamichi'
			],
			'conditions' => [
				'Kintaitran.flgmasters_id'=> [11,15,18,22,21],
			    'User.branchmasters_id' => $branchconditions,
			    'User.departmentmasters_id' => $departmentconditions,
			    'User.zaisekiflg' => 0,
				'User.kaisyas_id' => $kaisyaid,
				'Kintaitran.kintaidate >= ' => $firstDate,
				'Kintaitran.kintaidate <= ' => $paramsDate,
			],
			'order'=>[
				'User.username' => 'asc'
			],
			'group' => [
				'Kintaitran.users_id'
			]
		]);
	}

	public function SelectKintaitranHost016s($firstDate,$paramsDate,$branchconditions,$departmentconditions,$kaisyaid) {
		return $this->find('all', [
			'fields' => [
				'User.usernameK',
				'Kintaitran.kintaidate',
				'Kintaitran.shiharaisuryo',
				'Kintaitran.kousokudai',
			],
			'conditions' => [
			    'User.branchmasters_id' => $branchconditions,
			    'User.departmentmasters_id' => $departmentconditions,
			    'User.zaisekiflg' => 0,
				'User.kaisyas_id' => $kaisyaid,
				'User.rolemasters_id >=' => 30,
				'User.rolemasters_id <=' => 39,
				'Kintaitran.kintaidate >= ' => $firstDate,
				'Kintaitran.kintaidate <= ' => $paramsDate,
				'OR' => [
					'Kintaitran.shiharaisuryo' => 1,
					'Kintaitran.kousokudai' => 1
				]
				
			],
			'order'=>[
				'User.syain_cd' => 'asc',
				'Kintaitran.kintaidate' => 'asc'
			],
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
	    'users_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
	    'User' => array(
	        'className' => 'User',
	        'foreignKey' => 'users_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ),
	    'Flgmaster' => array(
	        'className' => 'Flgmaster',
	        'foreignKey' => 'flgmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ),
	);
}
