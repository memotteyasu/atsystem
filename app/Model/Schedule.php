<?php
App::uses('AppModel', 'Model');
/**
 * Reservetran Model
 *
 * @property Jobmasters $Jobmasters
 * @property Tantomasters $Tantomasters
 * @property Kaisyas $Kaisyas
 */
class Schedule extends AppModel {
	public $useTable = 'employeeschedules';

	public $validate = array(
		'users_id' => array(
			'rule' => 'numeric',
			'allowEmpty' => true,
			'message' => '従業員を選択してください。'
		),
		'scheduledate' => array(
			'rule' => 'date',
			'message' => '日付を入力してください。'
		),
		'schedulename' => array(
			'rule' => 'notBlank',
			'message' => '予定内容を入力してください。'
		)
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'users_id'
		)
	);

}
