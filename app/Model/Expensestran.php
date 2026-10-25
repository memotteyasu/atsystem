<?php
App::uses('AppModel', 'Model');

class Expensestran extends AppModel {

	public $name = 'Expensestran';
    public $useTable = 'expensestrans';

    // 主キーがID（大文字）のため明示
    public $primaryKey = 'id';

    public $belongsTo = array(

        'Shippermaster' => array(
            'className'  => 'Shippermaster',
            'foreignKey' => 'shippermasters_id'
        ),

        'Branchmaster' => array(
            'className'  => 'Branchmaster',
            'foreignKey' => 'branchmasters_id'
        ),

        'Kaisya' => array(
            'className'  => 'Kaisya',
            'foreignKey' => 'kaisyas_id'
        ),

        'Departmentmaster' => array(
            'className'  => 'Departmentmaster',
            'foreignKey' => 'departmentmasters_id'
        )
    );
}