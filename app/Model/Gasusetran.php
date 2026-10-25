<?php
App::uses('AppModel', 'Model');
/**
 * Gasusetran Model
 *
 */
class Gasusetran extends AppModel {
	public $name = 'Gasusetran';
	public $useTable = 'gasusetrans';

    // 検索プラグイン
    public $actsAs = array('Search.Searchable');

    // 検索対象のフィルタ設定
    public $filterArgs = [
		//['name' => 'chargedate', 'type' => 'value', 'field' => 'Gasusetran.chargedate'],
    	['name' => 'chargedate', 'type' => 'query', 'method' => 'datesql'],
//		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'User.departmentmasters_id'],
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'User.branchmasters_id'],
    ];

    public function datesql($data = array()) {
    	$firstdate = new DateTime($data['chargedate']);
    	$lastdate = new DateTime('last day of '.$firstdate->format('Y-m'));
    	$conditions = [
    		['Gasusetran.chargedate >=' => $firstdate->format('Y-m-d')],
    		['Gasusetran.chargedate <=' => $lastdate->format('Y-m-d')]
    	];
    	return $conditions;
    }

    // 検索対象のフィールド設定
    public $presetVars = [
		['field' => 'chargedate', 'type' => 'value'],
//		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'branchmasters_id', 'type' => 'value']
    ];

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
		'chargedate' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
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
	public $belongsTo = array(
	    'User' => [
	        'className' => 'User',
	        'foreignKey' => 'users_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	    'Flgmaster' => [
	        'className' => 'Flgmaster',
	        'foreignKey' => 'gaskind',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],

	);
}
