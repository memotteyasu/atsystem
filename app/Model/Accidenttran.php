<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Accidenttran extends AppModel {

/**
 * Use table
 */
    public $name = 'Accidenttran';
    public $useTable = 'accidenttrans';

    // 検索プラグイン
    public $actsAs = array('Search.Searchable');

    // 検索対象のフィルタ設定
    public $filterArgs = [
        ['name' => 'selectym1', 'type' => 'query', 'method' => 'datesql'],
    ];

    // 検索対象のフィールド設定
    public $presetVars = [
        ['field' => 'selectym1', 'type' => 'value'],
    ];
    public function datesql($data = array()) {
        $firstdate = new DateTime($data['selectym1']);
        $lastdate = new DateTime('last day of '.$firstdate->format('Y-m'));
        $conditions = [
            ['Accidenttran.selectym1 >=' => $firstdate->format('Y-m')],
            ['Accidenttran.selectym1 <=' => $lastdate->format('Y-m')]
        ];
        return $conditions;
    }

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
	    'User' => array(
	        'className' => 'User',
	        'foreignKey' => 'users_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    )
	);

}
