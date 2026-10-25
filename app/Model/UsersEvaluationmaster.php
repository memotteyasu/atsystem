<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class UsersEvaluationmaster extends AppModel {

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

	public function Selectkilotanka($uid,$today){
	    return $this->find('first', [
	        'fields' => ['evaluationkeisu'],
	        'conditions' => [
	            'users_id' => $uid,
	            'startdate <=' => $today,
	            'enddate >=' => $today
	        ],
	        'order' => ['startdate' => 'desc']
	    ]);

	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'users_id' => [
			'rule' => 'notBlank',
			'message' => '社員を選択してください'
		],
	];
	public $belongsTo =[
	    /*
	    'User' => [
	        'className' => 'User',
	        'foreignKey' => 'users_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	    */
/*
	    'Evaluationmaster' => [
	        'className' => 'Evaluationmaster',
	        'foreignKey' => 'evaluationmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
*/
	];
}
