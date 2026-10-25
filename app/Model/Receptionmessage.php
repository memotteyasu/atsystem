<?php
App::uses('AppModel', 'Model');
/**
 * Rceptionmessage Model
 *
 */
class Receptionmessage extends AppModel {


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
		'receptioncomment' => [
			'rule' => 'notBlank',
			'message' => 'メッセージを入力してください。'
		],
	];


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
		),
	);
}
