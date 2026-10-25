<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Employeemaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Employeemaster';
	public $useTable = 'employeemasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'employeename', 'type' => 'like', 'field' => 'Employeemaster.employeename')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'employeename', 'type' => 'value')
	);

/**
 * Validation rules
 *
 * @var array
 */
	/*
	public $validate = [
		'loadingcode' => [
			'rule' => 'notBlank',
			'message' => '積地CDを入力してください。'
		],
		'loadingname' => [
			'rule' => 'notBlank',
			'message' => '積地名称を入力してください。'
		],
		'loadingshortname' => [
			'rule' => 'notBlank',
			'message' => '積地略名称を入力してください。'
		],
		'loadingadd' => [
			'rule' => 'notBlank',
			'message' => '住所を入力してください。'
		],
		'loadingtel' => [
			'rule' => 'notBlank',
			'message' => '電話番号を入力してください。'
		],
	];
	*/

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo =[
		'Branchmaster' => [
			'className' => 'Branchmaster',
			'foreignKey' => 'branchmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];
}
