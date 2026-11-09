<?php
App::uses('AppModel', 'Model');

class VehicleSchedule extends AppModel {
	public $useTable = 'vehicleschedules';

	public $validate = array(
		'companyvehicles_id' => array(
			'rule' => 'numeric',
			'message' => '車両を選択してください。'
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
		'CompanyVehicle' => array(
			'className' => 'CompanyVehicle',
			'foreignKey' => 'companyvehicles_id'
		)
	);
}
