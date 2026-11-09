<?php
App::uses('AppController', 'Controller');

/**
 * 化学品 配車計画ボード
 */
class Ch031sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html', 'Form');
	public $uses = array('Branchmaster', 'Flgmaster', 'Kaisya', 'Processtran', 'User');
	public $components = array('SelectData');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->did = 2;
	}

	public function isAuthorized($user) {
		if (isset($user['rolemasters_id'])) {
			switch ($user['rolemasters_id']) {
				case 10:
				case 12:
				case 23:
				case 24:
				case 25:
				case 28:
					$this->set('user', $user);
					return true;
			}
		}
		return false;
	}

	/**
	 * 日別・車両別に工程を表示する。
	 */
	public function index() {
		$date = new DateTime();
		$requestedDate = $this->request->query('date');
		if ($requestedDate) {
			try {
				$date = new DateTime($requestedDate);
			} catch (Exception $e) {
				$this->Flash->error('日付の形式が正しくありません。');
			}
		}

		$branchmasters = $this->dispatchBranches();

		$branchmastersId = $this->request->query('branchmasters_id');
		if (!isset($branchmasters[$branchmastersId])) {
			$branchmastersId = $this->branchmasters_id;
		}
		if (!isset($branchmasters[$branchmastersId]) && !empty($branchmasters)) {
			$branchmastersId = key($branchmasters);
		}

		$paramsDate = $date->format('Y-m-d');
		$ordertrans = array();
		if ($branchmastersId) {
			$ordertrans = $this->SelectData->data_select_conditionsch030s(
				$paramsDate,
				$this->did,
				$branchmastersId,
				$this->kaisyas_id
			);
		}

		$partnerBranches = $this->partnerBranches();
		$driverCandidates = $this->driverCandidates($branchmasters);
		$vehicleLanes = array();
		$summary = array('unassigned' => 0, 'assigned' => 0, 'confirmed' => 0, 'partner_pending' => 0, 'processes' => 0);
		foreach ($ordertrans as $ordertran) {
			$plateNo = !empty($ordertran['Carmaster']['plateno']) ? $ordertran['Carmaster']['plateno'] : '車両未設定';
			if (!isset($vehicleLanes[$plateNo])) {
				$vehicleLanes[$plateNo] = array();
			}
			foreach ($ordertran['Processtran'] as $processtran) {
				if ($processtran['processstartdate'] !== $paramsDate) {
					continue;
				}
				$driverName = !empty($processtran['PDriver1']['usernameK']) ? $processtran['PDriver1']['usernameK'] : null;
				$assistantName = !empty($processtran['PDriver2']['usernameK']) ? $processtran['PDriver2']['usernameK'] : null;
				$partnerStatus = !empty($processtran['PartnerRequestStatus']['flgname']) ? $processtran['PartnerRequestStatus']['flgname'] : null;
				$partnerBranch = !empty($processtran['PartnerBranch']['branchname']) ? $processtran['PartnerBranch']['branchname'] : null;
				if (!empty($ordertran['Ordertran']['confirmflg'])) {
					$status = 'confirmed';
					$statusLabel = '確定済み';
					$summary['confirmed']++;
				} elseif ($partnerStatus && $partnerStatus !== '未依頼') {
					$status = 'partner';
					$statusLabel = '協力会社: ' . $partnerStatus;
					$summary['partner_pending']++;
				} elseif ($driverName) {
					$status = 'assigned';
					$statusLabel = '乗務員設定済み';
					$summary['assigned']++;
				} else {
					$status = 'unassigned';
					$statusLabel = '未配車';
					$summary['unassigned']++;
				}
				$summary['processes']++;
				$vehicleLanes[$plateNo][] = array(
					'order_id' => $ordertran['Ordertran']['id'],
					'process_id' => $processtran['id'],
					'time' => $processtran['processstarttime'],
					'workorder_no' => $processtran['workorder_no'],
					'workplace' => !empty($processtran['Workplace']['workplaceshortname']) ? $processtran['Workplace']['workplaceshortname'] : '',
					'workname' => !empty($processtran['Workdetailmaster']['workname']) ? $processtran['Workdetailmaster']['workname'] : '',
					'shipper' => !empty($ordertran['Shippermaster']['shippershortname']) ? $ordertran['Shippermaster']['shippershortname'] : '',
					'product' => !empty($processtran['Productcontainer']['pdconname']) ? $processtran['Productcontainer']['pdconname'] : '',
					'driver' => $driverName,
					'assistant' => $assistantName,
						'driver_id' => $processtran['pdriver1id'],
						'assistant_id' => $processtran['pdriver2id'],
					'container_no' => $processtran['container_no'],
					'partner_branch' => $partnerBranch,
					'partner_status' => $partnerStatus,
					'status' => $status,
					'status_label' => $statusLabel,
				);
			}
		}
		foreach ($vehicleLanes as &$lane) {
			usort($lane, function ($left, $right) {
				return strcmp($left['time'], $right['time']);
			});
		}
		unset($lane);

		$this->set(compact('paramsDate', 'branchmasters', 'branchmastersId', 'partnerBranches', 'driverCandidates', 'vehicleLanes', 'summary'));
	}

	public function assign_drivers($id = null) {
		$this->request->allowMethod('post');
		$processtran = $this->editableProcesstran($id);
		if (!$processtran) {
			throw new NotFoundException(__('対象工程が存在しません。'));
		}
		if (!empty($processtran['Processtran']['partner_request_flgmasters_id'])) {
			$this->Flash->error('協力会社への依頼中または回答済みの工程は、本社側では変更できません。');
			return $this->redirect($this->boardRedirect());
		}

		$driverCandidates = $this->driverCandidates($this->dispatchBranches());
		$driver1Id = $this->request->data('Processtran.pdriver1id');
		$driver2Id = $this->request->data('Processtran.pdriver2id');
		if (($driver1Id && !isset($driverCandidates[$driver1Id])) || ($driver2Id && !isset($driverCandidates[$driver2Id]))) {
			$this->Flash->error('選択された乗務員または助手は、配車対象の候補に含まれません。');
			return $this->redirect($this->boardRedirect());
		}
		$data = array('Processtran' => array(
			'id' => $id,
			'pdriver1id' => $driver1Id ? $driver1Id : null,
			'pdriver2id' => $driver2Id ? $driver2Id : null,
		));
		if ($this->Processtran->save($data, false)) {
			$this->Flash->success('乗務員・助手を登録しました。');
		} else {
			$this->Flash->error('乗務員・助手の登録に失敗しました。');
		}
		return $this->redirect($this->boardRedirect());
	}

	public function ajax_assign_driver() {
		$this->autoRender = false;
		$this->layout = 'ajax';
		$this->request->allowMethod('post');
		$id = $this->request->data('process_id');
		$field = $this->request->data('field');
		$driverId = $this->request->data('driver_id');
		if (!in_array($field, array('pdriver1id', 'pdriver2id'), true)) {
			return $this->ajaxResponse(false, '更新対象が不正です。');
		}
		$processtran = $this->editableProcesstran($id);
		if (!$processtran) {
			return $this->ajaxResponse(false, '対象工程が存在しません。');
		}
		if (!empty($processtran['Processtran']['partner_request_flgmasters_id'])) {
			return $this->ajaxResponse(false, '協力会社への依頼中または回答済みの工程は変更できません。');
		}
		$driverCandidates = $this->driverCandidates($this->dispatchBranches());
		if ($driverId && !isset($driverCandidates[$driverId])) {
			return $this->ajaxResponse(false, '選択された乗務員は配車対象の候補に含まれません。');
		}
		$ordertransId = $processtran['Processtran']['ordertrans_id'];
		$partnerRequestCount = $this->Processtran->find('count', array(
			'conditions' => array(
				'Processtran.ordertrans_id' => $ordertransId,
				'Processtran.delflg' => 0,
				'Processtran.partner_request_flgmasters_id !=' => null,
			),
			'recursive' => -1,
		));
		if ($partnerRequestCount > 0) {
			return $this->ajaxResponse(false, '協力会社へ依頼済みの工程が含まれるため、オーダー全体の乗務員変更はできません。');
		}
		$updateData = array(
			'Processtran.' . $field => $driverId ? (int)$driverId : 'NULL',
			'Processtran.modified_user' => Configure::read('logged_user_id'),
		);
		if (!$this->Processtran->updateAll($updateData, array(
			'Processtran.ordertrans_id' => $ordertransId,
			'Processtran.delflg' => 0,
		))) {
			return $this->ajaxResponse(false, '乗務員の登録に失敗しました。');
		}
		return $this->ajaxResponse(true, '同じオーダーの全工程へ保存しました。', array(
			'field' => $field,
			'ordertrans_id' => $ordertransId,
			'driver_name' => $driverId ? $driverCandidates[$driverId] : '未定',
		));
	}

	public function request_partner($id = null) {
		$this->request->allowMethod('post');
		$branchmastersId = $this->request->data('Processtran.partner_branchmasters_id');
		$partnerBranches = $this->partnerBranches();
		if (!$id || !isset($partnerBranches[$branchmastersId])) {
			$this->Flash->error('依頼先の協力会社を選択してください。');
			return $this->redirect($this->boardRedirect());
		}

		$processtran = $this->editableProcesstran($id);
		if (!$processtran) {
			throw new NotFoundException(__('対象工程が存在しません。'));
		}
		if (!empty($processtran['Processtran']['pdriver1id']) || !empty($processtran['Processtran']['pdriver2id'])) {
			$this->Flash->error('乗務員または助手が設定済みの工程には、協力会社依頼を登録できません。');
			return $this->redirect($this->boardRedirect());
		}

		$statusId = $this->Flgmaster->partnerRequestStatusId('依頼中');
		if (!$statusId) {
			$this->Flash->error('協力会社依頼ステータスが未登録です。');
			return $this->redirect($this->boardRedirect());
		}

		$data = array('Processtran' => array(
			'id' => $id,
			'partner_branchmasters_id' => $branchmastersId,
			'partner_request_flgmasters_id' => $statusId,
			'partner_requested_at' => date('Y-m-d H:i:s'),
			'partner_requested_user_id' => $this->Auth->user('id'),
			'partner_responded_at' => null,
			'partner_responded_user_id' => null,
		));
		if ($this->Processtran->save($data, false)) {
			$this->Flash->success('協力会社へ配車依頼しました。');
		} else {
			$this->Flash->error('協力会社への配車依頼に失敗しました。');
		}
		return $this->redirect($this->boardRedirect());
	}

	private function partnerBranches() {
		$partnerKaisyas = $this->Kaisya->find('list', array(
			'fields' => array('Kaisya.id', 'Kaisya.companyname'),
			'conditions' => array(
				'Kaisya.kaisyakey' => array('hinase', 'nanyou', 'kawagoe', 'takehara', 'meiwa'),
				'Kaisya.delflg' => 0,
			),
			'recursive' => -1,
		));
		$partnerUsers = $this->User->find('all', array(
			'fields' => array('User.kaisyas_id', 'User.branchmasters_id'),
			'conditions' => array(
				'OR' => array(
					array('User.kaisyas_id' => array_keys($partnerKaisyas), 'User.rolemasters_id' => 39),
					array('User.rolemasters_id' => 29, 'User.zaisekiflg' => 0),
				),
				'User.branchmasters_id !=' => null,
			),
			'recursive' => -1,
		));
		$partnerBranchCompanies = array();
		foreach ($partnerUsers as $partnerUser) {
			if (!empty($partnerUser['User']['branchmasters_id'])) {
				$partnerBranchCompanies[$partnerUser['User']['branchmasters_id']] = $partnerUser['User']['kaisyas_id'];
			}
		}
		if (empty($partnerBranchCompanies)) {
			return array();
		}
		$branches = $this->Branchmaster->find('list', array(
			'fields' => array('Branchmaster.id', 'Branchmaster.branchname'),
			'conditions' => array('Branchmaster.id' => array_keys($partnerBranchCompanies)),
			'order' => array('Branchmaster.id' => 'asc'),
			'recursive' => -1,
		));
		$partnerBranches = array();
		foreach ($branches as $branchId => $branchName) {
			$kaisyaId = $partnerBranchCompanies[$branchId];
			$kaisyaName = !empty($partnerKaisyas[$kaisyaId]) ? $partnerKaisyas[$kaisyaId] : '協力会社';
			$partnerBranches[$branchId] = $kaisyaName . '（' . $branchName . '）';
		}
		return $partnerBranches;
	}

	private function driverCandidates($branchmasters) {
		return $this->User->SelectUserDriversListchemi(
			$this->did,
			$branchmasters,
			$this->branchmasters_id,
			$this->branchgroups_id
		);
	}

	private function editableProcesstran($id) {
		$processtran = $this->Processtran->find('first', array(
			'conditions' => array('Processtran.id' => $id, 'Processtran.delflg' => 0),
			'contain' => array('Ordertran'),
			'recursive' => 0,
		));
		$allowedDispatchBranches = $this->dispatchBranches();
		if (empty($processtran) || empty($processtran['Ordertran']) ||
			$processtran['Ordertran']['departmentmasters_id'] != $this->did ||
			!isset($allowedDispatchBranches[$processtran['Ordertran']['branchmasters_id']])) {
			return null;
		}
		return $processtran;
	}

	private function dispatchBranches() {
		return $this->Branchmaster->find('list', array(
			'fields' => array('Branchmaster.id', 'Branchmaster.branchname'),
			'conditions' => array(
				'Branchmaster.kaisyas_id' => $this->kaisyas_id,
				'Branchmaster.branchgroups_id' => $this->branchgroups_id,
			),
			'order' => array('Branchmaster.id' => 'asc'),
			'recursive' => -1,
		));
	}

	private function boardRedirect() {
		return array('action' => 'index', '?' => array(
			'date' => $this->request->data('Processtran.return_date'),
			'branchmasters_id' => $this->request->data('Processtran.return_branchmasters_id'),
		));
	}

	private function ajaxResponse($success, $message, $data = array()) {
		$this->response->type('json');
		echo json_encode(array_merge(array('success' => $success, 'message' => $message), $data));
		return;
	}
}
