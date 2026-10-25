<?php
App::uses('AppModel', 'Model');
/**
 * Workprices Model
 *
 */
class Workprice extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Workprice';
	public $useTable = 'workprices';

	// 検索プラグイン
	public $actsAs = ['Search.Searchable','Containable'];

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'Workprice.branchmasters_id'],
	    ['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Workprice.departmentmasters_id'],
	    ['name' => 'roudouflg', 'type' => 'value', 'field' => 'Workprice.roudouflg'],
	    ['name' => 'buairank', 'type' => 'value', 'field' => 'Workprice.buairank'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'branchmasters_id', 'type' => 'value'],
	    ['field' => 'departmentmasters_id', 'type' => 'value'],
	    ['field' => 'roudouflg', 'type' => 'value'],
	    ['field' => 'buairank', 'type' => 'value']
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function Selectprice($wkm,$bid,$kid,$roudouflg,$buairank){
		//$wkm:走行キロ合計、$bid:branchmasters_id,$kid:会社ID,$roudouflg:労働区分、$buairank:歩合ランク
		return $this->find('first',[
			'fields'=>[
				'Workprice.price',
			    'Workprice.pricecomment',
			    'Workprice.zangyojikan',
			    'Workprice.shinyajikan',
			    'Workprice.untinbuai',
			    'Workprice.zangyotanka',
			    'Workprice.shinyatanka'
			],
			'conditions'=>[
				'Workprice.startkilo <=' => $wkm,
				'Workprice.endkilo >=' => $wkm,
				'Workprice.branchmasters_id'=>$bid,
			    'Workprice.kaisyas_id'=>$kid,
			    'Workprice.roudouflg'=>$roudouflg,
			    'Workprice.buairank'=>$buairank,
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
		'price' => [
			'rule' => 'notBlank',
			'message' => '価格を入力してください。'
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
		'Departmentmaster' => [
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Branchmaster' => [
			'className' => 'Branchmaster',
			'foreignKey' => 'branchmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];
}
