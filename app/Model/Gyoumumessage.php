<?php
App::uses('AppModel', 'Model');
/**
 * Gyoumumessage Model
 *
 * @property Kaisyas $Kaisyas
 * @property Departmentmasters $Departmentmasters
 */
class Gyoumumessage extends AppModel {

	public $name = 'Gyoumumessage';
	public $useTable = 'gyoumumessages';

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

	public $validate = [
		'gyoumucomment' => [
			'rule' => 'notBlank',
			'message' => 'メッセージを入力してください。'
		],
	];

	/*
	public $validate = array(
		'departmentmasters_id' => array(
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
	*/

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Kaisyas' => array(
			'className' => 'Kaisyas',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Departmentmasters' => array(
			'className' => 'Departmentmasters',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
