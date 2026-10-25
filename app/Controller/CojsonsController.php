<?php
class CojsonsController extends Controller {

	public $uses = ['Carmaster','Ordertran','Processtran'];
	public $components = ['Timediff'];

	public function beforeFilter(){
	   parent::beforeFilter();
	   $this->autoRender = false;
	   $this->autoLayout = false;
	   $this->response->type('json');
	}

	public function ajax() {
		//車両の可能に主に関係なく、更新処理をしている。
	    $oid = $this->params->pass[0];
	    $afterdate = $this->params->named['date'];
	    $aftercarid = $this->params->named['cid'];

	    //変更後のcarmasters_idでbranchmasters_idを取得
//	    $carmasters = $this->Carmaster->findById($aftercarid);
		$carmasters = $this->Carmaster->find('first',[
			'conditions'=>[
				'Carmaster.id' => $aftercarid
			],
			'fields'=>[
				'Carmaster.branchmasters_id'
			],
			'recursive'=>0
		]);
		$car_branchmasters_id = $carmasters['Carmaster']['branchmasters_id'];
		/* ordertranを読み込み、詳細情報を取得 */
		$ordertran = $this->Ordertran->find('first',[
	        'fields'=>[
	            'id','carmasters_id','loadingdate','deliverydate'
	        ],
	        'conditions'=>[
	            'Ordertran.id'=>$oid
	        ]
	    ]);
	    //processtranを読む
	    $processtrans = $this->Processtran->find('all',[
	        'fields'=>[
	            'Processtran.id',
	            'Processtran.processstartdate'
	        ],
	        'conditions'=>[
	            'Processtran.ordertrans_id'=>$oid
	        ],
	        'order'=>[
	            'Processtran.processstartdate'=>'asc'
	        ]
	    ]);
	    $i = 0;
		$dif_days = 0;
	    $startdate = $enddate = $w_date = $errormsg = '';

	    foreach($processtrans as $processtran){
	        $data2=$data3=[];
	        $fields2=$fields3=[];
	        if($i==0){
	            //まずは日付の差を取得
	            $timestamp1 = strtotime($processtran['Processtran']['processstartdate']);
	            $timestamp2 = strtotime($afterdate);
	            if($timestamp1==$timestamp2){
					//同じ日付なら車両を変更できる
	                $dif_days = 0;
	            }else{
					//違う日付に移動するとエラーにする。20230526
					$errormsg = 'dateerror';
					break;
	                //20230526 $dif = $timestamp2 - $timestamp1;
	                //20230526 $dif_days = (strtotime(date("Y-m-d", $dif)) - strtotime("1970-01-01")) / 86400;
	            }
	            //最初のデータの日付を退避
	            $w_date = $processtran['Processtran']['processstartdate'];
	            //1件目は無条件に日付を更新
	            /*
	            $data2 = ['Processtran',[
	                'id'=>$processtran['Processtran']['id'],
	                'processstartdate'=>$afterdate
	            ]];
	            $this->Processtran->save($data2);
	            */
	            $this->Processtran->id = $processtran['Processtran']['id'];
	            $this->Processtran->saveField('processstartdate',$afterdate);
	            $firstdate = $afterdate;
	        }else{
	            //2件目以降
	            $nextdate = new DateTime($processtran['Processtran']['processstartdate']);
	            $nextdate->modify($dif_days.' day');
	            $afterdate3 = $nextdate->format('Y-m-d');
	            /*
	            $data3 = ['Processtran',[
	                'id'=>$processtran['Processtran']['id'],
	                'processstartdate'=>$afterdate3
	            ]];
	            $this->Processtran->save($data3);
	            */
	            $this->Processtran->id = $processtran['Processtran']['id'];
	            $this->Processtran->saveField('processstartdate',$afterdate3);
	            $enddate = $afterdate3;
	        }
	        $i++;
	    }

		if($errormsg == ''){
			$data = ['Ordertran' =>['id'=>$oid,'carmasters_id'=>$aftercarid,'loadingdate'=>$firstdate,'deliverydate'=>$enddate,'branchmasters_id'=>$car_branchmasters_id]];
			$fields = ['carmasters_id','loadingdate','deliverydate','branchmasters_id'];

			$json = json_encode('ok');
			$this->response->body($json);
			$this->Ordertran->save($data,false,$fields);
		}else{
			$json = json_encode('ng');
			$this->response->body($json);
		}
/*
        $data2 = ['processstartdate'=>"'".$afterdate."'"];
        $conditions = [
            'Processtran.ordertrans_id'=>$oid
        ];
        $this->Processtran->updateAll($data2,$conditions);

        $fields2 = ['processstartdate'];
        $this->Processtran->save($data2,false,$fields2);
*/
	}


}
