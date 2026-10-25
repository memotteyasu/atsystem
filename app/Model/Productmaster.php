<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Productmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Productmaster';
	public $useTable = 'productmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'pname', 'type' => 'like', 'field' => 'Productmaster.pname')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'pname', 'type' => 'value')
	);

	public function Select($productmasters_id){
		return $this->find( 'all', [
			'recursive' => 2,
			'conditions' => [
				['Productmaster.id' => $productmasters_id]
			]
		]);
	}

	public function SelectList(){
		return $this->find('list', [
			'fields' => ['id','pname'],
			'conditions' => '',
			'order' => ['id' => 'asc']
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'pcode' => [
			'rule' => 'notBlank',
			'message' => '商品CDを入力してください。'
		],
		'pname' => [
			'rule' => 'notBlank',
			'message' => '商品名称を入力してください。'
		],
	];

	public $hasAndBelongsToMany = [
		'Carmaster' => [
			'className' => 'Carmaster',
			'joinTable' => 'productmasters_has_carmasters',
			'foreignKey' => 'productmasters_id',
			'associationForeignKey' => 'carmasters_id',
			'unique' => true
		]
	];
}
