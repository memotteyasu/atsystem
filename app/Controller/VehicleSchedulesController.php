<?php
App::uses('AppController', 'Controller');

class VehicleSchedulesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html', 'Form');
	public $uses = array('VehicleSchedule', 'CompanyVehicle');

	public function isAuthorized($user) {
		if (isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 10 && $user['rolemasters_id'] <= 29) {
			if ($user['rolemasters_id'] >= 20) {
				$this->layout = 'layout_clerk';
			}
			$this->set('user', $user);
			return true;
		}
		return false;
	}

	public function index() {
		$weekOffset = isset($this->request->query['week_offset']) ? (int)$this->request->query['week_offset'] : 0;
		$weekOffset = max(-11, min($weekOffset, 11));
		$weekStart = new DateTime('today');
		$weekStart->modify('+' . ($weekOffset * 7) . ' days');
		$weekEnd = clone $weekStart;
		$weekEnd->modify('+6 days');
		$weekdayLabels = array('月', '火', '水', '木', '金', '土', '日');
		$weekDates = array();
		$weekOptions = array();
		for ($i = -11; $i < 12; $i++) {
			$optionStart = new DateTime('today');
			$optionStart->modify('+' . ($i * 7) . ' days');
			$optionEnd = clone $optionStart;
			$optionEnd->modify('+6 days');
			$weekOptions[$i] = $optionStart->format('n/j') . '〜' . $optionEnd->format('n/j');
		}
		for ($i = 0; $i < 7; $i++) {
			$weekDate = clone $weekStart;
			$weekDate->modify('+' . $i . ' days');
			$weekDates[] = array('date' => $weekDate->format('Y-m-d'), 'label' => $weekDate->format('n/j') . '（' . $weekdayLabels[$weekDate->format('N') - 1] . '）', 'weekday' => (int)$weekDate->format('N'));
		}
		$vehicles = $this->_vehicles();
		$vehicleSchedules = $this->VehicleSchedule->find('all', array(
			'fields' => array('VehicleSchedule.companyvehicles_id', 'VehicleSchedule.scheduledate', 'VehicleSchedule.schedulename', 'VehicleSchedule.displaycolor'),
			'conditions' => array(
				'VehicleSchedule.kaisyas_id' => $this->Auth->user('kaisyas_id'),
				'VehicleSchedule.scheduledate >=' => $weekStart->format('Y-m-d'),
				'VehicleSchedule.scheduledate <=' => $weekEnd->format('Y-m-d'),
				'VehicleSchedule.delflg' => 0
			),
			'recursive' => -1
		));
		$entriesByVehicleAndDate = array();
		foreach ($vehicleSchedules as $vehicleSchedule) {
			$entriesByVehicleAndDate[$vehicleSchedule['VehicleSchedule']['companyvehicles_id']][$vehicleSchedule['VehicleSchedule']['scheduledate']][] = array(
				'name' => $vehicleSchedule['VehicleSchedule']['schedulename'],
				'color' => $vehicleSchedule['VehicleSchedule']['displaycolor']
			);
		}
		$this->set(compact('weekStart', 'weekEnd', 'weekOffset', 'weekDates', 'weekOptions', 'vehicles', 'entriesByVehicleAndDate'));
	}

	public function add() {
		$vehicles = $this->_vehicles();
		$fixedVehicle = null;
		$query = !empty($this->request->query) ? $this->request->query : array();
		if ($this->request->is('post')) {
			if (empty($this->request->data['VehicleSchedule']['companyvehicles_id']) || !isset($vehicles[$this->request->data['VehicleSchedule']['companyvehicles_id']])) {
				$this->Flash->error('車両を選択してください。');
			} else {
				$this->request->data['VehicleSchedule']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
				$this->VehicleSchedule->create();
				if ($this->VehicleSchedule->save($this->request->data)) {
					$this->Flash->success('車両予定を登録しました。');
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error('車両予定を登録できませんでした。入力内容を確認してください。');
			}
		} elseif (isset($query['companyvehicles_id'], $query['scheduledate'])
			&& isset($vehicles[$query['companyvehicles_id']])
			&& preg_match('/^\d{4}-\d{2}-\d{2}$/', $query['scheduledate'])) {
			$this->request->data['VehicleSchedule'] = array('companyvehicles_id' => $query['companyvehicles_id'], 'scheduledate' => $query['scheduledate'], 'displaycolor' => '#5bc0de');
			$fixedVehicle = array('id' => $query['companyvehicles_id'], 'name' => $vehicles[$query['companyvehicles_id']]);
		}
		$this->set(compact('vehicles', 'fixedVehicle'));
	}

	protected function _vehicles() {
		return $this->CompanyVehicle->find('list', array(
			'fields' => array('CompanyVehicle.id', 'CompanyVehicle.vehiclename'),
			'conditions' => array('CompanyVehicle.kaisyas_id' => $this->Auth->user('kaisyas_id'), 'CompanyVehicle.delflg' => 0),
			'order' => array('CompanyVehicle.displayno' => 'asc', 'CompanyVehicle.vehiclename' => 'asc'),
			'recursive' => -1
		));
	}
}
