<?php
App::uses('AppController', 'Controller');
/**
 * Ch000 Controller
 *
 * @property Ch000
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Ch000sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Ordertran','Holiday');
    public $helpers = array('Html','Form');
/**
 * Components
 *
 * @var array
 */
    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);
    }


    public function isAuthorized($user){
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
            $this->set('user',$user);
            return true;
        }
        return false;
        break;
    }

/**
 * index method
 *
 * @return void
 */
	public function index() {
	    $ordertrans = $this->Ordertran->find('all');
	    $this->set(compact('ordertrans'));

	}
	public function fcfeed(){
		$wkid = $this->Auth->user('kaisyas_id');
		$ordertrans = $this->Ordertran->find('all', array(
			'conditions' => array(
				'Ordertran.kaisyas_id' => $wkid,
				'OR' => array(
					'Ordertran.delflg' =>'',
					'Ordertran.delflg' =>0
				)
			),
			'order' => array('Ordertran.deliverydate'=>'asc')
		));
		$rows = array();
		for($a=0; count($ordertrans) > $a; $a++){
			/*
			if ($ordertrans[$a]['Ordertran']['holidayflg'] == 1) {
				$w_title = $ordertrans[$a]['Loadingmaster']['loadingname'].'/'.$ordertrans[$a]['Delivery']['deliveryname'];
				$color = '#ffffff';
				$textColor = '#ff3300';
			}
			*/
			$w_title = $ordertrans[$a]['Loadingmaster']['loadingname'].'/'.$ordertrans[$a]['Deliverymaster']['deliveryname'];
			$rows[]=array(
				'id' => $ordertrans[$a]['Ordertran']['id'],
				'title' => $w_title,
				'start' => $ordertrans[$a]['Ordertran']['loadingdate'],
				'end' => $ordertrans[$a]['Ordertran']['deliverydate'],
				//'color' => $color,
				//'textColor' => $textColor,
				'url' => Router::url(array('controller'=>'HostTruckmasters', 'action'=>'edit')).'/'.$ordertrans[$a]['Ordertran']['id'],
				'allDaySlot' => true,
				'allDayText' => '終日',
			);
		}
		echo json_encode($rows);
	}

	public function fcfeed2($id,$yy,$mm,$dd){
	    $reservetrans = $this->Reservetran->findById($id);
	    $sdate = new DateTime($reservetrans['Reservetran']['startdate']);
	    $edate = new DateTime($reservetrans['Reservetran']['enddate']);
	    $shh = $sdate->format('H');
	    $sii = $sdate->format('i');
	    $ehh = $edate->format('H');
	    $eii = $edate->format('i');

	    $this->Reservetran->id = $id;
	    $data = array(
	        'Reservetran' => array(
	            'id' => $id,
	            'startdate' => $yy.'-'.$mm.'-'.$dd.'/'.$shh.':'.$sii,
	            'enddate' => $yy.'-'.$mm.'-'.$dd.'/'.$ehh.':'.$eii,
	        )
	    );
	    $this->Reservetran->save($data);
	    $this->redirect(array('controller'=>'ManagerTops','action'=>'index'));
	}

	public function fcholiday(){
		$holidays = $this->Holiday->find('all');
		$rows = array();
		for($a=0; count($holidays) > $a; $a++){
			$w_title = $holidays[$a]['Holiday']['holidayname'];
			$rows[]=array(
				'id' => $holidays[$a]['Holiday']['id']+10,
				'title' => $w_title,
				'start' => $holidays[$a]['Holiday']['startdate'],
				'end' => $holidays[$a]['Holiday']['enddate'],
				'color' => $holidays[$a]['Holiday']['color']
			);
		}
		echo json_encode($rows);
	}
}
