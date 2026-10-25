<?php
App::uses('AppModel', 'Model');
/**
 * Calendarmaster Model
 *
 */
class Calendarmaster extends AppModel {

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

    public function calenlist($paramsdate,$kaisyaid){
        $calen = $this->find('first', [
            'fields' => 'id,restflg',
            'conditions' => [
                'restdate' => $paramsdate,
                'kaisyas_id'=>$kaisyaid,
            ]
        ]);
        return $calen;
    }

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'restdate' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'restflg' => array(
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
}
