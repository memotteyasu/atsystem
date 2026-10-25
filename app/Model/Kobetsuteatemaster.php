<?php
App::uses('AppModel', 'Model');
/**
 * Kobetsuteatemaster Model
 *
 */
class Kobetsuteatemaster extends AppModel {
	    var $virtualFields = [
	        'tname' => 'concat(Kobetsuteatemaster.kobetsukilo, "分  ", Kobetsuteatemaster.teatename)'
	    ];
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


	public function Selectlist($kid){
	    return $this->find('list',[
		    'fields'=>[
		        'id',
		        'tname'
		    ],
			'conditions'=>[
			    'Kobetsuteatemaster.kaisyas_id'=>$kid,
			    'Kobetsuteatemaster.delflg'=>0
			],
	        'order'=>[
	            'Kobetsuteatemaster.teatename',
	            'Kobetsuteatemaster.kobetsukilo'
	        ]
		]);
	}
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Kaisya' => [
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];
}
