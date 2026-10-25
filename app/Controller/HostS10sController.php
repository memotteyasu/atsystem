<?php
//2023-03-18 60超歩合（時間）、60超残業（金額）を追加
use Cake\Chronos\Date;

App::uses('AppController', 'Controller');
/**
 * HostS10s Controller
 *
 * @property HostS10 HostS10s
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostS10sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Csv','Format');
	public $uses = ['Personalmessage','Gyoumumessage','User','Kintaitran','Calendarusermaster','Salarytran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		//$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
			$this->set('user',$user);
			return true;
		}
		return false;
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$date = new DateTime();
		$date2 = new DateTime();

		//直近の給与確定状況を取得
		$salarytrans = $this->Salarytran->find('all',[
			'order'=>[
				'salarydate'=>'desc'
			],
			'limit'=>5
		]);

		if ($this->request->is('post')) {
			list($date,$lastdate) = $this->SelectData->date_select_month($this->request->data('selectym1'));
			$date2 = $this->SelectData->date_select($this->request->data('selectym2'));
		}
		
		//2022-04-28 なぜかｃｓｖでエラーになっために修正した。$paramsDate = $date->format('Y-m-d');
		$paramsDate = $date->modify('first day of this months');
		$paramsDate = $paramsDate->format('Y-m-d');
		//20240925 前月を表示
		$paramsDateprev = $date->modify('first day of -1 months');
		$paramsDateprev = $paramsDateprev->format('Y-m-d');
		
		$paramsDate2 = $date2->format('Y-m-d');

		$this->set(compact('paramsDate','paramsDate2','paramsDateprev','salarytrans'));
	}

	public function csv($date,$date2) {
	    $selectmm = new DateTime($date);
	    $paramsDate = new DateTime($date);
	    $paramsDate2 = new DateTime($date2);
		$printday = new DateTime();
		$firstdate = new DateTime($date);
		$firstdate->modify('first day of this months');
		$lastdate = new DateTime($date);
		$lastdate->modify('last day of this months');
		$w_firstdate = $firstdate->format('Y-m-d');
		$w_lastdate = $lastdate->format('Y-m-d');

		// レイアウトを使用しない
		$this->layout = '';
        $kaisyaname = $this->Auth->user('Kaisya.kaisyakey');
		$filename = $kaisyaname.$paramsDate->format('Ym_').$printday->format('Ymd');

		$th = [
			'社員コード','支給回数月','支給日',
		    '要勤務日数','出勤日数','欠勤日数','休業日数','代休日数','休日出勤日数','有給消化日数','遅刻早退回数','遅刻早退時間',
			'超過時間外','超過深夜割増','超過休日残業','超過休深割増','超過法定残業','超過法定割増',
			'距離歩合','個別歩合','電話歩合','夜勤歩合','通常残業','乗務残業','資格歩合','法定外歩合','法定休日歩合','ガソリン代',
		    '指定特休日数','総勤務時間','定額時間外','定額深夜',
		    '超過時間外金額','超過深夜割増金額','超過休日残業金額','超過休深割増金額','超過法定金額','超過法定割増金額','歩合割増','夜勤歩合時間','定額時間外歩合時間','定額深夜歩合時間','実際残業時間','実際深夜残業時間','特休日数','非課税交通費','課税交通費','乗務深夜','守衛１','守衛２','守衛３','60超歩合','60超残業','60超歩合金額','60超残業金額','乗務時間','残業差','教育時間'
		];

		$datas = $this->User->find('all', [
			'conditions' => [
				'User.zaisekiflg' => 0,
//			    'User.id'=>28, //金子さん
				'User.kaisyas_id' => $this->kaisyas_id,
				'User.salaryflg !=' => 5, // 給与区分が対象外
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'Kintaitran.kintaidate >= ' => $firstdate->format('Y-m-d'),
						'Kintaitran.kintaidate <= ' => $lastdate->format('Y-m-d'),
					],
				],
			],
		]);
		$this->set(compact('filename','th','datas','paramsDate','paramsDate2','selectmm','w_firstdate','w_lastdate'));

	}
	//給与確定処理
	public function salaryconfirm($date,$date2){
		//salarytranへ書き込み
	    $paramsDate = new DateTime($date);
		//すでに確定済みの確認
		$salarytran = $this->Salarytran->findBySalarydate($date);
		if($salarytran){
			//存在してたら
			$this->Flash->error('すでに確定済みです');
		}else{
			$this->Salarytran->create();
			$this->Salarytran->save([
				'salarydate' => $paramsDate->format('Y-m-d'),
				'kaisyas_id' => $this->kaisyas_id,
				'created_user' => $this->Auth->user('id')
			]);
			$this->Flash->success('確定しました。');
			//kintaitranのデータをkintaitran2へコピー（指定の月のデータを）
			//kintaitranから２年前の当月のデータをを削除
			//kintaitran2にて拘束時間を集計する。
			//
		}

		$this->redirect(['controller' => 'HostS10s','action' => 'index']);

}
}
