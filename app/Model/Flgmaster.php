<?php
App::uses('AppModel', 'Model');
/**
 * Flgmaster Model
 *
 */
class Flgmaster extends AppModel {
    public $name = 'Flgmaster';
    public $useTable = 'flgmasters';

    public function beforeSave($option = []) {
    	if(empty($this->id)){
    		$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
    	}
    	$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
    	return true;
    }

    public function flgchk($flg){
        /*
         * flggroup
         * 1:勤怠
         * 3:油種
         * 4:有休
         */
        if(!$flg){
            $flg=1; // defaultは1にする。
        }
        $flgmasters = $this->find('list',[
            'fields' => ['id','flgname'],
            'conditions' => ['flggroup'=>$flg]
        ]);
        return $flgmasters;
    }
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'flgname' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'flggroup' => array(
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

	public $hasMany = [
		'Kintaitran' => [
			'className' => 'Kintaitran',
			'foreignKey' => 'flgmasters_id',
			'dependent' => false
		],
		'Employeeholiday' => [
			'className' => 'Employeeholiday',
			'foreignKey' => 'flgmasters_id',
			'dependent' => false
		],
	];
}
