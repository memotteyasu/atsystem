<?php
App::uses('AppModel', 'Model');
/**
 * Calendarusermaster Model
 *
 */
class Calendarusermaster extends AppModel {

	public $name = 'Calendarusermaster';
	public $useTable = 'calendarusermasters';

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	// 2020-06-30 users_idからcalendartypeに変更
	public function usercalenlist($paramsdate,$cid){
	    $selectdate = New Datetime($paramsdate);
	    $checkdate = New Datetime($paramsdate);
        $first_date = $checkdate->modify('first day of this months');
        $check_first_date = $first_date->format('Y-m-d');

        $wwday = null;
        $select_day = $selectdate->format('d');
	    $$wwday = 'day'.$select_day.' AS restflg';
	    $calen = $this->find('first', [
	        'fields' => [
	            'id',
	            $$wwday,
	        ],
	        'conditions' => [
	            'month' => $check_first_date,
	            'calendartype'=>$cid,
	        ]
	    ]);
	    return $calen;
	}

/**
 * Validation rules
 *
 * @var array
 */


	public $belongsTo = [
/*
	    'User' => [
			'className' => 'User',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
*/
		'Kaisya' => [
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];

}
