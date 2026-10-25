<?php
App::uses('AppModel', 'Model');
/**
 * Personalmessage Model
 *
 * @property Users $Users
 */
class Personalmessage extends AppModel {

	public $name = 'Personalmessage';
	public $useTable = 'personalmessages';

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	// The Associations below have been created with all possible keys, those that are not needed can be removed
    public $validate = [
        'personalcomment' => [
            'rule' => 'notBlank',
            'message' => 'コメントを入力してください。'
        ]
    ];

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
		)
	);
}
