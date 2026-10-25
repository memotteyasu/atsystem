<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class KintaitranKobetsuteatemaster extends AppModel {
    public $name = 'KintaitranKobetsuteatemaster';
    public $useTable = 'kintaitrans_kobetsuteatemasters';

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


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'kobetsuteatemasters_id' => [
			'rule' => 'notBlank',
			'message' => '正しく入力してください'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Kintaitran' => [
			'className' => 'Kintaitran',
			'foreignKey' => 'kintaitrans_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Kobetsuteatemaster' => [
			'className' => 'Kobetsuteatemaster',
			'foreignKey' => 'kobetsuteatemasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];
}
