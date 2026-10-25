<?php
class CalendarsController extends AppController {
	public $layout = 'layout_host';
	public $uses = ['User','Holiday'];
	public $helpers = array('Html','Form');

	public function beforeFilter() {
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

	public function index() {

	}

	public function fcfeed(){
		$wkid = $this->Auth->user('kaisyas_id');
		$reservetrans = $this->Reservetran->find('all', array(
				'conditions' => array(
						'Reservetran.kaisyas_id' => $wkid,
						'OR' => array(
								'Reservetran.delflg' =>'',
								'Reservetran.delflg' =>0
						)
				),
				'order' => array('Reservetran.startdate'=>'asc', 'Reservetran.jobmasters_id'=>'asc')
		));
		$rows = array();
		for($a=0; count($reservetrans) > $a; $a++){
			if ($reservetrans[$a]['Reservetran']['holidayflg'] == 1) {
				$w_title = $reservetrans[$a]['Tantomaster']['tantonameshort'].'/'.$reservetrans[$a]['Jobmaster']['operationnameshort'];
				$color = '#ffffff';
				$textColor = '#ff3300';
			} else {
				if ($reservetrans[$a]['Reservetran']['customername'] == null) {
					$w_title = $reservetrans[$a]['Reservetran']['customersection'].'/'.$reservetrans[$a]['Reservetran']['customertanto'].'/'.$reservetrans[$a]['Reservetran']['work'];
				} else {
					$w_title = $reservetrans[$a]['Reservetran']['customername'].'/'.$reservetrans[$a]['Reservetran']['customersection'].'/'.$reservetrans[$a]['Reservetran']['customertanto'].'/'.$reservetrans[$a]['Reservetran']['work'];
				}
				$color = $reservetrans[$a]['Tantomaster']['tantocolor'];
				$textColor = '';
			}
			$rows[]=array(
					'id' => $reservetrans[$a]['Reservetran']['id'],
					'title' => $w_title,
					'start' => $reservetrans[$a]['Reservetran']['startdate'],
					'end' => $reservetrans[$a]['Reservetran']['enddate'],
					'color' => $color,
					'textColor' => $textColor,
					'url' => Router::url(array('controller'=>'ManagerReserves', 'action'=>'edit')).'/'.$reservetrans[$a]['Reservetran']['id'],
					'allDay' => $reservetrans[$a]['Reservetran']['allDay'],
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
