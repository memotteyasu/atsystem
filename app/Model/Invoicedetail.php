<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Invoicedetail extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Invoicedetail';
	public $useTable = 'invoicedetails';

	public function beforeSave($option = []) {
	    if(empty($this->id)){
	        $this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
	    }
	    $this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
	    return true;
	}


	public $belongsTo =[
		'Invoice' => [
			'className' => 'Invoice',
			'foreignKey' => 'invoices_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		    'dependent' => true
		],
		/*
		'Productcontainer' => [
			'className' => 'Productcontainer',
			'foreignKey' => 'productcontainers_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		    'dependent' => true
		]
		*/
	];

}
