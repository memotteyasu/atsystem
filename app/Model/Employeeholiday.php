<?php
App::uses('AppModel', 'Model');
/**
 * Employeeholiday Model
 *
 * @property Users $Users
 * @property  $
 */
class Employeeholiday extends AppModel {
	public $name = 'Employeeholiday';
	public $useTable = 'employeeholidays';

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
	public $validate = array(
		'applicationdate' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'users_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
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
	    'Flgmaster2' => array(
	        'className' => 'Flgmaster',
	        'foreignKey' => 'flgmasters_id2',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ),
	);
}
