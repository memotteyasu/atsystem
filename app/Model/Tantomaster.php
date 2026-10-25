<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Tantomaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $name = 'Tantomaster';
    public $useTable = 'tantomasters';

    // 検索プラグイン
    public $actsAs = ['Search.Searchable'];

    // 検索対象のフィルタ設定
    public $filterArgs = [
        ['name' => 'shippermasters_id', 'type' => 'value', 'field' => 'Tantomaster.shippermasters_id'],
        ['name' => 'tantoname', 'type' => 'like', 'field' => 'Tantomaster.tantoname'],
    ];

    // 検索対象のフィールド設定
    public $presetVars = [
        ['field' => 'shippermasters_id', 'type' => 'value'],
        ['field' => 'tantoname', 'type' => 'value']
        //['field' => 'id', 'type' => 'value'],
    ];

    public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 担当者セレクトリスト
	 */
	public function SelectTantoList($sid){
		$tantomasters = $this->find('list', [
			'fields' => ['id','tantoname'],
			'conditions' => [
			    'shippermasters_id'=>$sid,
			    'delflg' => 0
			],
			'order' => ['shippermasters_id','tantoname']
		]);
		if($tantomasters){
		    return $tantomasters;
		}else{
		    //return [0=>'なし'];
		}
	}

	public $belongsTo = array(
	    'Shippermaster' => array(
	        'className' => 'Shippermaster',
	        'foreignKey' => 'Shippermasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    )
    );
}
