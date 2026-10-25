<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Gridtest extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Gridtest';
	public $useTable = 'tests';

	public function Select($tyy1,$tmm1,$tdd1,$productdetailmasters_id){
		$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		$firstDate = $date->format('Y-m-d');
		$lastDate = $date->modify('+6 day')->format('Y-m-d');
		return $this->find( 'all', [
			'order' => [
				'Gridtest.sortflg' => 'desc',
				'Gridtest.start_date' => 'asc',
				'Gridtest.end_date' => 'asc',
				'Gridtest.carmasters_id' => 'asc'
			],
			'recursive' => 2,
			'conditions' => ['OR'=>[
				[
					['Gridtest.start_date >= '=>$firstDate],
					['Gridtest.start_date <= '=>$lastDate],
					['Gridtest.productdetailmasters_id' => $productdetailmasters_id],
					['Gridtest.delflg' => 0]
				],
				[
					['Gridtest.end_date >= '=>$firstDate],
					['Gridtest.end_date <= '=>$lastDate],
					['Gridtest.productdetailmasters_id' => $productdetailmasters_id],
					['Gridtest.delflg' => 0]
				]
			]]
			/*
			'conditions' => ['AND'=>[
				['Gridtest.start_date >= '=>$firstDate],
				['Gridtest.start_date <= '=>$lastDate],
				['Gridtest.productmasters_id' => $productmasters_id]
			]]
			*/
		]);
	}

	public function Select2($tyy1,$tmm1,$tdd1){
		$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		$firstDate = $date->format('Y-m-d');
		$lastDate = $date->modify('+6 day')->format('Y-m-d');

		/*
		$month1 = $tyy1.'-'.$tmm1.'-'.$tdd1;
		$firstDate = date('Y-m-d', strtotime($month1));
		$month2 = $tyy1.'-'.$tmm1.'-'.$tdd2;
		$lastDate = date('Y-m-d', strtotime($month2));
		*/
		return $this->find( 'all', [
			'order' => [
				'Gridtest.sortflg' => 'desc',
				'Gridtest.start_date' => 'asc',
				'Gridtest.end_date' => 'asc',
				'Gridtest.carmasters_id' => 'asc'
			],
			'recursive' => 2,
			'conditions' => ['OR'=>[
				[
					['Gridtest.start_date >= '=>$firstDate],
					['Gridtest.start_date <= '=>$lastDate],
					['Gridtest.delflg' => 0]
				],
				[
					['Gridtest.end_date >= '=>$firstDate],
					['Gridtest.end_date <= '=>$lastDate],
					['Gridtest.delflg' => 0]
				]
			]]
				/*
				'conditions' => ['AND'=>[
						['Gridtest.start_date >= '=>$firstDate],
						['Gridtest.start_date <= '=>$lastDate]
				]]
				*/
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'name' => [
			'rule' => 'notBlank',
			'message' => '名称を入力してください。'
		],
		'carmasters_id' => [
			'rule' => 'notBlank',
			'message' => '車番を選択してください。'
		],
	];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Productdetailmaster' => array(
			'className' => 'Productdetailmaster',
			'foreignKey' => 'productdetailmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Carmaster' => array(
			'className' => 'Carmaster',
			'foreignKey' => 'carmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
