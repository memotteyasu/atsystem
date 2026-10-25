<?php
App::uses('AppController', 'Controller');
/**
 * Co051 Controller
 *
 * Oi051 copy
 */
class Co051sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster');
    public $components = ['Timediff'];

    public function beforeFilter(){
    	parent::beforeFilter();
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 12;
                case 23; //事務職（コンテナ）
                case 24; //事務職（全て）
                case 25; //事務職（化学品コンテナ）
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
	public function index($id = null, $lid = null, $dlid =null) {
		$did = 4;

		$orderlists = $this->Processtran->SelectProcesstran051s2($id,$lid,$dlid);
		$processtrans = $this->Processtran->SelectProcesstran051s($id,$lid,$dlid);
		$this->set(compact('orderlists','processtrans'));

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList($did);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
		$this->set(compact('deliverymasters','shippermasters','productdetailmasters','loadingmasters'));
	}

	public function copy($id = null, $oid = null) {
		$ordertrans = $this->Ordertran->SelectOrdertran051s($oid);
		$processtrans = $this->Processtran->SelectProcesstran051s3($id);

		$newstartdate = $ordertrans['Ordertran']['deliverydate'];
		$newstarttime = $ordertrans['Ordertran']['deliverytime'];
		$newstartdatetime = $newstartdate.' '.$newstarttime;

		/* 1.卸作業のキー（2）をもつ配列データを抽出　（卸が必ずあるわけではない） */
		$key2=0;
		foreach ($processtrans as $keycheck){
		    /*
			if($keycheck['Processtran']['workdetailmasters_id'] == 2){
				$keystartdate = $keycheck['Processtran']['processstartdate'];
				$keystarttime = $keycheck['Processtran']['processstarttime'];
				$keystartdatetime = $keystartdate.' '.$keystarttime;
				$keyenddate = $keycheck['Processtran']['processstartdate'];
				$keyendtime = $keycheck['Processtran']['processstarttime'];
				$keyenddatetime = $keyenddate.' '.$keyendtime;
				$key2 = 1;
			}
			*/
		    if(($keycheck['Workdetailmaster']['workname'] == '卸')or($keycheck['Workdetailmaster']['workname'] == '実入OFF')){
			    /* 卸の日時を待避（2019-09-25 add)  */
			    $keystartdate = $keycheck['Processtran']['processstartdate'];
			    $keystarttime = $keycheck['Processtran']['processstarttime'];
			    $keystartdatetime = $keystartdate.' '.$keystarttime;
			    $keyenddate = $keycheck['Processtran']['processstartdate'];
			    $keyendtime = $keycheck['Processtran']['processstarttime'];
			    $keyenddatetime = $keyenddate.' '.$keyendtime;
			    $key2 = 1; // 卸データがある場合は１をたてる
			}
		}
		if($key2==0){
		    // 卸データがない場合はオーダーの届け先日時を卸の日時としてセット
		    $keystartdate = $keycheck['Processtran']['processstartdate'];
		    $keystarttime = $keycheck['Processtran']['processstarttime'];
		    $keystartdatetime = $keystartdate.' '.$keystarttime;
		    $keyenddate = $keycheck['Processtran']['processstartdate'];
		    $keyendtime = $keycheck['Processtran']['processstarttime'];
		    $keyenddatetime = $keyenddate.' '.$keyendtime;
		}
		$this->request->data = $processtrans;
		$i = 0;
		foreach($this->request->data as $workarea){
			/* 1レコードずつprocessstartdate,timeとの時間の差を計算 */
			$startdate = $workarea['Processtran']['processstartdate'];
			$starttime = $workarea['Processtran']['processstarttime'];
			$startdatetime = $startdate.' '.$starttime;
			$diffdatetime1 = $this->Timediff->time_diff($keystartdatetime,$startdatetime);
			$currectdatetime1 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime1);

// 			$enddate = $workarea['Processtran']['processenddate'];
// 			$endtime = $workarea['Processtran']['processendtime'];
// 			$enddatetime = $enddate.' '.$endtime;
// 			$diffdatetime2 = $this->Timediff->time_diff($keyenddatetime,$enddatetime);
// 			$currectdatetime2 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime2);

			$this->request->data[$i]['Processtran']['id'] = null;
			$this->request->data[$i]['Processtran']['ordertrans_id'] = $oid;
			$this->request->data[$i]['Processtran']['processstartdate'] = date('Y-m-d',$currectdatetime1);
			//$this->request->data[$i]['Processtran']['processstarttime'] = date('H:i:s',$currectdatetime1);
			//$this->request->data[$i]['Processtran']['processenddate'] = date('Y-m-d',$currectdatetime2);
			//$this->request->data[$i]['Processtran']['processendtime'] = date('H:i:s',$currectdatetime2);
			$this->request->data[$i]['Processtran']['created'] = date('Y-m-d H:i:s');
			$this->request->data[$i]['Processtran']['modified'] = null;
			$this->request->data[$i]['Processtran']['users_id'] = null;
			//debug($this->request->data[$i]['Processtran']);
			$i = $i + 1;
		}
		if ($this->Processtran->saveAll($this->request->data)) {
			$this->Flash->success(__('工程を複製しました。'));
			return $this->redirect(['controller' => 'Co050s','action' => 'add', $oid]);
		} else {
			$this->Flash->error(__('複製に失敗しました。'));
		}
	}

	/* 削除予定　様子見
    public function copy2($id = null, $oid = null, $lid = null, $did =null) {
        	if (!$this->Ordertran->exists($id)) {
        		throw new NotFoundException(__('存在しません'));
        	}
        	$ordertrans = $this->Ordertran->find('first', [
        		'conditions' => [
        			'Ordertran.id' => $id,
        		],
        		'contain' => [
        			'Processtran' => [
        				'conditions' => [
        					'OR' => [
        						['Processtran.delflg' => ''],
        						['Processtran.delflg' => 0]
        					]
        				],
        			    'order' => [
        			        ['Processtran.processstartdate']
        			    ]
        			]
        		],
        		'recursive' => 1,
        	]);

        	$ordertrans2 = $this->Ordertran->findById($oid);

        	if (!$ordertrans['Processtran']) {
        		$this->Flash->error(__('選択したオーダーには工程が存在していません。'));
        		return $this->redirect(['controller' => 'Co051s','action' => 'index', $oid, $lid, $did]);
        	} else {
        	    $newstartdate = $ordertrans2['Ordertran']['deliverydate'];
        	    $newstarttime = $ordertrans2['Ordertran']['deliverytime'];
        	    $newstartdatetime = $newstartdate.' '.$newstarttime;
            // 1.卸作業のキー（2）をもつ配列データを抽出　（卸が必ずあるわけではない

        	    foreach ($ordertrans['Processtran'] as $keycheck){
        	        if($keycheck['workdetailmasters_id']==2){
        	            // 卸の日時を待避
        	            $keystartdate = $keycheck['processstartdate'];
        	            $keystarttime = $keycheck['processstarttime'];
        	            $keystartdatetime = $keystartdate.' '.$keystarttime;
        	            $keyenddate = $keycheck['processstartdate'];
        	            $keyendtime = $keycheck['processstarttime'];
        	            $keyenddatetime = $keyenddate.' '.$keyendtime;
        	        }
        	    }
        	    $this->request->data = $ordertrans['Processtran'];
        	    $i = 0;
        	    foreach($this->request->data as $workarea){
        	        // 1レコードずつprocessstartdate,timeとの時間の差を計算
        	        $startdate = $workarea['processstartdate'];
        	        $starttime = $workarea['processstarttime'];
        	        $startdatetime = $startdate.' '.$starttime;
        	        $diffdatetime1 = $this->Timediff->time_diff($keystartdatetime,$startdatetime);
        	        $currectdatetime1 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime1);

        	        $enddate = $workarea['processenddate'];
        	        $endtime = $workarea['processendtime'];
        	        $enddatetime = $enddate.' '.$endtime;
        	        $diffdatetime2 = $this->Timediff->time_diff($keyenddatetime,$enddatetime);
        	        $currectdatetime2 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime2);

        	        $this->request->data[$i]['id'] = null;
        	        $this->request->data[$i]['ordertrans_id'] = $oid;
        	        $this->request->data[$i]['processstartdate'] = date('Y-m-d',$currectdatetime1);
        	        $this->request->data[$i]['processstarttime'] = date('H:i:s',$currectdatetime1);
        	        $this->request->data[$i]['processenddate'] = date('Y-m-d',$currectdatetime2);
        	        $this->request->data[$i]['processendtime'] = date('H:i:s',$currectdatetime2);
        	        $this->request->data[$i]['created'] = date('Y-m-d H:i:s');
        	        $this->request->data[$i]['modified'] = null;
        	        $this->request->data[$i]['users_id'] = null;
        	        $i = $i + 1;
        	    }
        	    // debug($this->request->data);
        		if ($this->Processtran->saveAll($this->request->data)) {
        			$this->Flash->success(__('工程をコピーしました。'));
        			return $this->redirect(['controller' => 'Co050s','action' => 'add', $oid]);
        		} else {
        			$this->Flash->error(__('失敗しました。'));
        			return $this->redirect(['controller' => 'Co051s','action' => 'index', $oid, $lid, $did]);
        		}
        	}
    }
    */

}
