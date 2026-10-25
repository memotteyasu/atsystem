<?php
App::uses('AppController', 'Controller');
/**
 * Host061s Controller
 *
 * @property Kintaitran $Kintaitran
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class Host061sController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
    public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Calendarmaster','Calendarusermaster');
    public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

    public function beforeFilter(){
        parent::beforeFilter();
        $this->page = 'Host061s';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11: //管理者
                case 12: //管理者
                $this->set('user', $user);
                return true;
                break;
            }
        }
        return false;
        break;
    }


/**
 * index method
 *
 * @return void
 */
    public function index($did = null) {
	    $page = $this->page;

	    if ($this->request->is('post')) {
	        $date = new DateTime($this->request->data['selectym1']['year'].'-'.$this->request->data['selectym1']['month']);
	        $paramsDate = $date->format('Y-m-d');
	    }else{
	        $date = new DateTime();
	        $paramsDate = $date->format('Y-m-d');
	    }

	    $week = ['日','月','火','水','木','金','土'];
	    $this->set(compact('paramsDate','week'));

	    $users = $this->SelectData->data_select_conditionsHost061s($paramsDate,$this->kaisyas_id);
	    $this->set('users', $users);
	    $this->set('did',$did);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */



	public function pdf($date,$bid) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
		if ($bid == 0) {
			$bconditions = [];
		} else {
			$bconditions = ['User.branchmasters_id' => $bid];
		}

		$week = ['日','月','火','水','木','金','土'];
		$paramsDate = new DateTime($date);
		$paramsDate2 = new DateTime($date);
		$paramsDate3 = new DateTime($date);

		$firstday = $paramsDate2->modify('first day of this months');
		$lastday = $paramsDate3->modify('last day of this months');
		$this->set(compact('paramsDate','firstday','lastday','week'));

		// $this->Kintaitran->Behaviors->load('EagerLoader.EagerLoader');

		$Kintaitrans = $this->Kintaitran->find('all', [
			'fields' => [
				'Kintaitran.id',
				'Kintaitran.kintaidate',
				'User.id',
				'User.syain_cd',
				'User.usernameK'
			],
			'conditions' => [
				'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
				'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
				$bconditions,
				'Kintaitran.kaisyas_id'=>$this->kaisyas_id,
			],
		    'order'=>[
		        'Kintaitran.users_id'=>'asc',
		        'Kintaitran.kintaidate'
		    ],
			'recursive' => 0,
		]);

		$flgmasters = $this->Flgmaster->flgchk(3);

		$this->set(compact('Kintaitrans','flgmasters'));

	}
}
