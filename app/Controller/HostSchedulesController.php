<?php
App::uses('AppController', 'Controller');

class HostSchedulesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html', 'Form');
	public $uses = array('Schedule', 'User');

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
		$schedules = $this->Schedule->find('all', array(
			'conditions' => array(
				'User.kaisyas_id' => $this->Auth->user('kaisyas_id'),
				'Schedule.scheduledate >=' => date('Y-m-d'),
				'Schedule.delflg' => 0
			),
			'order' => array('Schedule.scheduledate' => 'asc', 'User.displayno' => 'asc'),
			'recursive' => 0
		));
		$companySchedules = $this->Schedule->find('all', array(
			'conditions' => array(
				'Schedule.kaisyas_id' => $this->Auth->user('kaisyas_id'),
				'Schedule.scheduledate >=' => date('Y-m-d'),
				'Schedule.delflg' => 0
			),
			'order' => array('Schedule.scheduledate' => 'asc'),
			'recursive' => -1
		));
		$schedules = array_merge($schedules, $companySchedules);
		usort($schedules, function ($left, $right) {
			return strcmp($left['Schedule']['scheduledate'], $right['Schedule']['scheduledate']);
		});
		$this->set(compact('schedules'));
	}

	public function add() {
		$scheduleUsers = array('__company__' => '社内全体') + $this->_scheduleUsers();
		$fixedScheduleUser = null;
		$fixedCompanySchedule = false;
		$query = !empty($this->request->query)
			? $this->request->query
			: (isset($this->request->params['url']) ? $this->request->params['url'] : array());
		if ($this->request->is('post')) {
			if (!empty($this->request->data['fixed_user'])
				&& !empty($this->request->data['Schedule']['users_id'])
				&& isset($scheduleUsers[$this->request->data['Schedule']['users_id']])) {
				$fixedScheduleUser = array(
					'id' => $this->request->data['Schedule']['users_id'],
					'name' => $scheduleUsers[$this->request->data['Schedule']['users_id']]
				);
			}
			$fixedCompanySchedule = !empty($this->request->data['company_schedule'])
				|| (isset($this->request->data['Schedule']['users_id']) && $this->request->data['Schedule']['users_id'] === '__company__');
			if ($fixedCompanySchedule) {
				$this->request->data['Schedule']['users_id'] = null;
				$this->request->data['Schedule']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			} elseif (empty($this->request->data['Schedule']['users_id']) || !isset($scheduleUsers[$this->request->data['Schedule']['users_id']])) {
				$this->Flash->error('従業員を選択してください。');
				$this->set('scheduleUsers', $scheduleUsers);
				$this->set('fixedScheduleUser', $fixedScheduleUser);
				$this->set('fixedCompanySchedule', $fixedCompanySchedule);
				return;
			}
			$this->Schedule->create();
			if ($this->Schedule->save($this->request->data)) {
				$this->Flash->success('予定を登録しました。');
				if (!empty($this->request->data['fixed_user']) || !empty($this->request->data['company_schedule'])) {
					$calendarTopController = $this->Auth->user('rolemasters_id') >= 20 ? 'ClerkTops' : 'HostTops';
					return $this->redirect(array('controller' => $calendarTopController, 'action' => 'index'));
				}
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error('予定を登録できませんでした。入力内容を確認してください。');
		} elseif (!empty($query['company_schedule'])
			&& isset($query['scheduledate'])
			&& preg_match('/^\d{4}-\d{2}-\d{2}$/', $query['scheduledate'])) {
			$this->request->data['Schedule'] = array(
				'scheduledate' => $query['scheduledate'],
				'displaycolor' => '#5bc0de'
			);
			$fixedCompanySchedule = true;
		} elseif (isset($query['users_id'], $query['scheduledate'])
			&& isset($scheduleUsers[$query['users_id']])
			&& preg_match('/^\d{4}-\d{2}-\d{2}$/', $query['scheduledate'])) {
			$this->request->data['Schedule'] = array(
				'users_id' => $query['users_id'],
				'scheduledate' => $query['scheduledate'],
				'displaycolor' => '#5bc0de'
			);
			$fixedScheduleUser = array(
				'id' => $query['users_id'],
				'name' => $scheduleUsers[$query['users_id']]
			);
		}
		$this->set('scheduleUsers', $scheduleUsers);
		$this->set('fixedScheduleUser', $fixedScheduleUser);
		$this->set('fixedCompanySchedule', $fixedCompanySchedule);
	}

	public function edit($id = null) {
		$schedule = $this->_scheduleForCurrentCompany($id);
		if (!$schedule) {
			throw new NotFoundException('予定が見つかりません。');
		}
		if ($this->request->is(array('post', 'put'))) {
			if (!empty($schedule['Schedule']['kaisyas_id'])) {
				$this->request->data['Schedule']['users_id'] = null;
				$this->request->data['Schedule']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			}
			$this->Schedule->id = $id;
			if ($this->Schedule->save($this->request->data)) {
				$this->Flash->success('予定を更新しました。');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error('予定を更新できませんでした。入力内容を確認してください。');
		} elseif (!$this->request->data) {
			$this->request->data = $schedule;
		}
		$this->set('scheduleUsers', $this->_scheduleUsers());
		$this->set('fixedCompanySchedule', !empty($schedule['Schedule']['kaisyas_id']));
	}

	public function delete($id = null) {
		$this->request->allowMethod('post', 'delete');
		$schedule = $this->_scheduleForCurrentCompany($id);
		if ($schedule) {
			$this->Schedule->id = $id;
			$this->Schedule->saveField('delflg', 1);
			$this->Flash->success('予定を削除しました。');
		}
		return $this->redirect(array('action' => 'index'));
	}

	protected function _scheduleUsers() {
		return $this->User->find('list', array(
			'fields' => array('User.id', 'User.usernameK'),
			'conditions' => array('User.kaisyas_id' => $this->Auth->user('kaisyas_id'), 'User.zaisekiflg' => 0, 'User.rolemasters_id <=' => 28),
			'order' => array('User.displayno' => 'asc', 'User.usernameK' => 'asc'),
			'recursive' => -1
		));
	}

	protected function _scheduleForCurrentCompany($id) {
		$schedule = $this->Schedule->find('first', array(
			'conditions' => array('Schedule.id' => $id, 'Schedule.delflg' => 0, 'User.kaisyas_id' => $this->Auth->user('kaisyas_id')),
			'recursive' => 0
		));
		if ($schedule) {
			return $schedule;
		}
		return $this->Schedule->find('first', array(
			'conditions' => array('Schedule.id' => $id, 'Schedule.delflg' => 0, 'Schedule.kaisyas_id' => $this->Auth->user('kaisyas_id')),
			'recursive' => -1
		));
	}
}
