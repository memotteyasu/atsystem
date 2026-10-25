<?php
App::uses('AppModel', 'Model');
/**
 * Workprice2s Model
 *
 */
class Workprice2 extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Workprice2';
	public $useTable = 'workprice2s';

	// 検索プラグイン
	public $actsAs = ['Search.Searchable','Containable'];

	// 検索対象のフィルタ設定
	public $filterArgs = [
	];

	// 検索対象のフィールド設定
	public $presetVars = [
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function Selectprice($wkm,$kid){
	    //$wkm:走行キロ合計、$kid:会社ID
	    return $this->find('first',[
	        'fields'=>[
	            'Workprice2.startkilo',
	            'Workprice2.endkilo',
	            'Workprice2.zangyojikan',
	            'Workprice2.shinyajikan',
	        ],
	        'conditions'=>[
	            'Workprice2.startkilo <=' => $wkm,
	            'Workprice2.endkilo >=' => $wkm,
	            'Workprice2.kaisyas_id'=>$kid,
	            'Workprice2.delflg'=>0
	        ]
	    ]);
	}
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'startkilo' => [
			'rule' => 'notBlank',
			'message' => 'キロを入力してください。'
		],
		'endkilo' => [
			'rule' => 'notBlank',
			'message' => 'キロを入力してください。'
		],
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
