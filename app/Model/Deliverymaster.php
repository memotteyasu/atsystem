<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Deliverymaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Deliverymaster';
	public $useTable = 'deliverymasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Deliverymaster.departmentmasters_id'],
		['name' => 'areamasters_id', 'type' => 'value', 'field' => 'Deliverymaster.areamasters_id'],
		['name' => 'deliveryname', 'type' => 'like', 'field' => 'Deliverymaster.deliveryname'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'areamasters_id', 'type' => 'value'],
		['field' => 'deliveryname', 'type' => 'value']
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * Ch000s/Co000s/Oi000s納入先リスト絞り込み用
	 */
	public function SelectDeliveryList($did){
		return $this->find( 'list', [
			'fields' => ['Deliverymaster.id','Deliverymaster.deliveryshortname'],
			'order' => ['Deliverymaster.deliverynamekana' => 'asc'],
			'conditions' => [
				'Deliverymaster.departmentmasters_id' => $did,
				'Deliverymaster.delflg' => 0
			]
		]);
	}

	public function SelectDeliveryList2($did){
		return $this->find( 'list', [
			'fields' => ['Deliverymaster.id','Deliverymaster.deliveryshortname'],
			'order' => ['Deliverymaster.deliverynamekana' => 'asc'],
			'conditions' => [
			    'Deliverymaster.delflg' => 0,
			    'Deliverymaster.departmentmasters_id'=>$did
			]
		]);
	}


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'deliveryname' => [
			'rule' => 'notBlank',
			'message' => '納入先名称を入力してください。'
		],
		'deliveryshortname' => [
			'rule' => 'notBlank',
			'message' => '納入先略名称を入力してください。'
		],
/*
		'areamasters_id' => [
			'rule' => 'notBlank',
			'message' => '地域を選択してください。'
		],
*/
	];

/**
 * belongsTo associations
 *
 * @var array
 */

	public $hasMany = [
		'Deliverymasterimage' => [
			'className' => 'Deliverymasterimage',
			'foreignKey' => 'deliverymasters_id',
			'conditions' => ['Deliverymasterimage.delflg' => 0],
			'fields' => [
				'id',
				'img_file_name',
				'imgurl',
				'imgcomment'
			]
		]
	];

	public $belongsTo = array(
		'Departmentmaster' => array(
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Areamaster' => array(
			'className' => 'Areamaster',
			'foreignKey' => 'areamasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
