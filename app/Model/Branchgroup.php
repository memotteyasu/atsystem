<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Branchgroup extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Branchgroup';
	public $useTable = 'branchgroups';


	public function BranchgroupList() {
		return $this->find('list',[
			'fields' => ['id','branchgroupname'],
			'order' => ['id' => 'asc']
		]);
	}

	public function BranchgroupList2($bgid) {
		return $this->find('list',[
			'conditions' => ['id'=>$bgid],
			'fields' => ['id','branchgroupname'],
			'order' => ['id' => 'asc']
		]);
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

}
