<?php
App::uses('AppModel', 'Model');

class CompanyVehicle extends AppModel {
	public $useTable = 'companyvehicles';

	public $validate = array(
		'vehiclename' => array('rule' => 'notBlank', 'message' => '車両名を入力してください。'),
		'plateno' => array('rule' => 'notBlank', 'message' => 'ナンバーを入力してください。'),
		'branchmasters_id' => array('rule' => 'numeric', 'message' => '所属営業所を選択してください。')
	);

	public $belongsTo = array(
		'Branchmaster' => array('className' => 'Branchmaster', 'foreignKey' => 'branchmasters_id')
	);
}
