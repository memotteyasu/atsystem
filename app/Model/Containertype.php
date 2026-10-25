<?php
App::uses('AppModel', 'Model');
/**
 * Containertype Model
 *
 */
class Containertype extends AppModel {

	public $name = 'Containertype';
	public $useTable = 'containertypes';

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

    public function ContainertypeList(){
        /*
         * 1:無
         * 2:２軸ショート
         * 3:２軸ロング
         * 4:３軸ショート
         * 5:３軸ロング
         */
            return $this->find('list', [
                'fields' => ['id','contype'],
                'conditions'=>['delflg'=>0],
            ]);
    }
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'contype' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

}
