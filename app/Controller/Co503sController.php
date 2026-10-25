<?php
App::uses('AppController', 'Controller');
/**
 * Co503 Controller
 *
 * @copy Co003
 */
class Co503sController extends AppController {
	public $layout = 'layout_partner';
	public $uses = array('User','Kaisya','Branchmaster','Branchgroup','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Holiday','Carmaster','Processtran','Workdetailmaster','Kintaitran','Productcontainer','Trailermaster','Workplace','Tantomaster','Ordertranimage');
	public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
	public $components = ['SelectData'];
/**
 * Components
 *
 * @var array
 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co503s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
                case 10: //管理者
                case 12;
				case 29; //事務職（協力会社）
				$this->set('user', $user);
					return true;
					break;
			}
		}
		return false;
	}

/**
 * index method
 *
 * @return void
 */

	public function index() {
		$did = $this->did;
		$page = $this->page;

		$shippermasters_id = null;
        $date = new DateTime();
		// 先にSessionがある場合初期値を設定
		if ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				if ($this->Session->check('BackData2.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				}
			}
			$this->Session->delete('BackData2');
		} elseif ($this->Session->check('BackData1')) {
			if ($this->Session->read('BackData1.Page') == $page) {
				if ($this->Session->check('BackData1.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				}
			} else {
				$this->Session->delete('BackData1');
			}
		}
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));

			if (isset($this->request->data['last1day'])) {
				$date->modify('-1 days');
			}
			if (isset($this->request->data['next1day'])) {
				$date->modify('+1 days');
			}
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'),
					'month' => $date->format('m'),
					'day' => $date->format('d')
				],
				'Page' => $page
			];
			$this->Session->write('BackData1', $backdatas);
			return $this->redirect(['action' => 'index']);
		}
		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','shippermasters_id','branchgroups_id'));
		$selectData = $this->SelectData->data_select_conditionsCo503s($paramsDate,$paramsDate2,$did,$this->branchmasters_id);
		$carmasters = $selectData['carmasters'];
		$ordertrans = $selectData['ordertrans'];

		$notruncarmasters = $this->Carmaster->NotRunCar($paramsDate,$paramsDate2);
		$drivers = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate2);

		$this->set(compact('notruncarmasters','carmasters','drivers','ordertrans'));
	}
	public function view($id = null) {
	    $ordertran = $this->Ordertran->find('first',[
	        'conditions'=>[
	            'Ordertran.id'=>$id
	        ],
	        'contain'=>[
	            'Processtran'=>[
	                'order'=>[
	                    'processstartdate',
	                    'processstarttime',
	                    'id',
	                ],
	                'fields'=>[
	                    'Processtran.id',
	                    'Processtran.workddetailcomment',
	                    'Processtran.ordertrans_id',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.pdriver1id',
	                    'Processtran.pdriver2id',
	                    'Processtran.workdetailmasters_id',
	                    'Processtran.workplaces_id',
	                    'Processtran.productcontainers_id',
	                    'Processtran.container_no',
	                    'Processtran.ref_no'
	                ],
	                'conditions'=>[
	                    'Processtran.delflg'=>0
	                ]
	            ],
	            'Shippermaster',
	            'Carmaster',
	            'Productdetailmaster',
	            'Driver1',
	            'Driver2',
	            'Productcontainer',
	            'Workplace1',
	            'Workplace2',
	            'Processtran',
	            'Tantomaster',
	            'Ordertranimage'
	        ]
	    ]);

	    $this->set(compact('ordertran'));

	    if ($this->request->is('post')) {
	        if ($this->request->data['Ordertranimage']['img']['error'] == 0) {
	            $this->Ordertranimage->create();
	            $this->request->data['Ordertranimage']['ordertrans_id']=$id;
	            if ($this->Ordertranimage->saveAll($this->request->data)) {
	                $this->Flash->success('画像をアップロードしました');
	                return $this->redirect(['action' => 'view', $id]);
	            } else {
	                $this->Flash->error('画像のアップロードに失敗しました');
	            }
	        } else {
	            $this->Flash->error('画像が選択されていません');
	        }
	    }
	}

}
