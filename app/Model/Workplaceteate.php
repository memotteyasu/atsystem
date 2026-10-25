<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Workplaceteate extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}



/**
 * Validation rules
 *
 * @var array
 */
// 	public $validate = [
// 		'loadingname' => [
// 			'rule' => 'notBlank',
// 			'message' => '積地名称を入力してください。'
// 		],
// 		'loadingshortname' => [
// 			'rule' => 'notBlank',
// 			'message' => '積地略名称を入力してください。'
// 		],
// /*
// 	    'loadingadd' => [
// 			'rule' => 'notBlank',
// 			'message' => '住所を入力してください。'
// 		],
// 		'loadingtel' => [
// 			'rule' => 'notBlank',
// 			'message' => '電話番号を入力してください。'
// 		],
// */
// 	];

/**
 * belongsTo associations
 *
 * @var array
 */

 	public $belongsTo =[
 		'Workplace' => [
 			'className' => 'Workplace',
 			'foreignKey' => 'workplaces_id',
 			'conditions' => '',
 			'fields' => '',
 			'order' => ''
 		],
 		'Branchmaster' => [
 			'className' => 'Branchmaster',
 			'foreignKey' => 'branchmasters_id',
 			'conditions' => '',
 			'fields' => '',
 			'order' => ''
 		]
 	];
}
