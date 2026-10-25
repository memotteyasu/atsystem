<?php
App::uses('AppController', 'Controller');

class SelectDataComponent extends Component{
	public $components = ['Auth'];
	//public $uses = ['Truckkind'];

	/*
	 * 日付のセレクト
	 */
	function date_select($selectdate) {
		$tyy1 = $selectdate['year'];
		$tmm1 = $selectdate['month'];
		$tdd1 = $selectdate['day'];
		$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		return $date;
	}
	function date_select_month($selectdate) {
		$tyy1 = $selectdate['year'];
		$tmm1 = $selectdate['month'];
		$date = new DateTime($tyy1.'-'.$tmm1);
		$date->modify('first day of this months');
		$date2 = new DateTime($tyy1.'-'.$tmm1);
		$date2->modify('last day of this months');
		return [$date,$date2];
	}
	function date_select_year($selectdate) {
		$tyy1 = $selectdate['year'];
		$date = new DateTime($tyy1.'-01-01');
		$date2 = new DateTime($tyy1.'-12-31');
		return [$date,$date2];
	}

	/*
	 * 日付・荷主・商品のセレクトによるデータ切り替え（000s系列用）
	 */
	function data_select_conditions000s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Truckkind');
		$userModel2 = ClassRegistry::init('Productdetailmaster');

		if (!$shippermasters_id) {
			$shipperconditions = [];
			$productdetailmasters = $userModel2->pdnameList($did);
		} else {
			$shipperconditions = ['Truckkind.shippermasters_id' => $shippermasters_id];
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		}
		if (!$productdetailmasters_id) {
			$productconditions = [];
		} else {
			$productconditions = ['Truckkind.productdetailmasters_id' => $productdetailmasters_id];
		}
		if (!$branchmasters_id) {
			$branchconditions = [
				['Carmaster.departmentmasters_id' => $did]
			];
		} else {
			$branchconditions = [
				['Carmaster.branchmasters_id' => $branchmasters_id],
				['Carmaster.departmentmasters_id' => $did]
			];
		}
		// $branchconditions = [
		// 	['Carmaster.branchmasters_id' => $branchmasters_id],
		// 	['Carmaster.departmentmasters_id' => $did]
		// ];
		if (!$shippermasters_id && !$productdetailmasters_id && !$branchmasters_id) {
			$truckkinds = [];
		} else {
		    switch($did){
		        case 1: //石油
		            $truckkinds = $userModel->SelectTruckkind000s($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions);
		            break;
		        case 2: //化学品
		            $truckkinds = $userModel->SelectTruckkind000sc($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions);
		            break;
		        case 3:
		            break;
		        case 4: //コンテナ
		            $truckkinds = $userModel->SelectTruckkind000s($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions);
		            break;
		        default:
		            $truckkinds = $userModel->SelectTruckkind000s($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions);
		            break;
		    }
		}

		return ['truckkinds' => $truckkinds, 'pdlist' => $productdetailmasters];
	}

	/*
	 * 20211216 Ch003s用
	 */
	function data_select_conditionsCh003s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$branchgroups_id) {
		$userModel = ClassRegistry::init('Truckkind');
		$userModel2 = ClassRegistry::init('Productdetailmaster');
		$userModel3 = ClassRegistry::init('Branchmaster');
	    $branchmasters = $userModel3->find('list',[
	        'fields'=>[
	            'id'
	        ],
	        'conditions'=>[
	            'branchgroups_id'=>$branchgroups_id
	        ]
	    ]);

		if (!$shippermasters_id) {
			$shipperconditions = [];
			$productdetailmasters = $userModel2->pdnameList($did);
		} else {
			$shipperconditions = ['Truckkind.shippermasters_id' => $shippermasters_id];
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		}
		if (!$productdetailmasters_id) {
			$productconditions = [];
		} else {
			$productconditions = ['Truckkind.productdetailmasters_id' => $productdetailmasters_id];
		}
		if (!$branchgroups_id) {
			$branchconditions = [
				['Carmaster.departmentmasters_id' => $did]
			];
	        $branchconditions2 = [];
		} else {
			$branchconditions = [
				['Carmaster.branchmasters_id' => $branchmasters],
				['Carmaster.departmentmasters_id' => $did]
			];
	        $branchconditions2 = ['Carmaster.branchmasters_id' => $branchmasters];
		}
		// $branchconditions = [
		// 	['Carmaster.branchmasters_id' => $branchmasters_id],
		// 	['Carmaster.departmentmasters_id' => $did]
		// ];
		if (!$shippermasters_id && !$productdetailmasters_id && !$branchgroups_id) {
			$truckkinds = [];
		} else {
			$truckkinds = $userModel->SelectTruckkind000sc($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions2);
		}

		return ['truckkinds' => $truckkinds, 'pdlist' => $productdetailmasters];
	}

	/*
	 * 20201013 Co000s用
	 */
	function data_select_conditionsCo000s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Truckkind');
		$userModel2 = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
			$shipperconditions2 = [];
		} else {
			$shipperconditions = ['Truckkind.shippermasters_id' => $shippermasters_id];
			$shipperconditions2 = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$branchmasters_id) {
			$branchconditions = [
				['Carmaster.departmentmasters_id' => $did]
			];
			$branchconditions2 = [];
		} else {
			$branchconditions = [
				['Carmaster.branchmasters_id' => $branchmasters_id],
				['Carmaster.departmentmasters_id' => $did]
			];
			$branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters_id];
		}
		if (!$shippermasters_id && !$branchmasters_id) {
			$truckkinds = [];
			$ordertrans = [];
		} else {
			$truckkinds = $userModel->SelectTruckkindCo000sTest($paramsDate,$paramsDate2,$shipperconditions,$branchconditions);
			$ordertrans = $userModel2->SelectOrdertranCo000s($paramsDate,$paramsDate2,$did,$shipperconditions2,$branchconditions2);
		}

		return ['truckkinds' => $truckkinds, 'ordertrans' => $ordertrans];
	}
/*
 * Co000用
 */
	function data_select_conditionsCo0001s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchgroups_id) {
	    $userModel = ClassRegistry::init('Truckkind');
	    $userModel2 = ClassRegistry::init('Ordertran');
	    $userModel3 = ClassRegistry::init('Branchmaster');

	    $branchmasters = $userModel3->find('list',[
	        'fields'=>[
	            'id'
	        ],
	        'conditions'=>[
	            'branchgroups_id'=>$branchgroups_id
	        ]
	    ]);
	    if (!$shippermasters_id) {
	        $shipperconditions = [];
	        $shipperconditions2 = [];
	    } else {
	        $shipperconditions = ['Truckkind.shippermasters_id' => $shippermasters_id];
	        $shipperconditions2 = ['Ordertran.shippermasters_id' => $shippermasters_id];
	    }
	    if (!$branchgroups_id) {
	        $branchconditions = [
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = [];
	    } else {
	        $branchconditions = [
	            ['Carmaster.branchmasters_id' => $branchmasters],
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters];
	    }
	    if (!$shippermasters_id) {
	        $truckkinds = [];
	        $ordertrans = [];
	    } else {
	        $truckkinds = $userModel->SelectTruckkindCo003s($paramsDate,$paramsDate2,$shipperconditions,$branchconditions);
	        $ordertrans = $userModel2->SelectOrdertranCo003s($paramsDate,$paramsDate2,$did,$shipperconditions2,$branchconditions2);
	    }

	    return ['truckkinds' => $truckkinds, 'ordertrans' => $ordertrans];
	}
	/*
	 * Co003用
	 * 20251118 cid追加
	 */
	function data_select_conditionsCo003s($paramsDate,$paramsDate2,$did,$branchgroups_id,$cid) {
	    $userModel = ClassRegistry::init('Carmaster');
	    $userModel2 = ClassRegistry::init('Ordertran');
	    $userModel3 = ClassRegistry::init('Branchmaster');

	    $branchmasters = $userModel3->find('list',[
	        'fields'=>[
	            'id'
	        ],
	        'conditions'=>[
	            'branchgroups_id'=>$branchgroups_id
	        ]
	    ]);
		if(!$cid){
			$carconditions = [];
		}else{
			$carconditions = ['Ordertran.carmasters_id' => $cid];
		}
	    if (!$branchgroups_id) {
	        $branchconditions = [
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = [];
	    } else {
	        $branchconditions = [
	            ['Carmaster.branchmasters_id' => $branchmasters],
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters];
	    }
	    //truckkindではなく、carmaster
        $carmasters = $userModel->SelectCarmasterCo003s($paramsDate,$paramsDate2,$branchconditions,$cid);
        $ordertrans = $userModel2->SelectOrdertranCo003s($paramsDate,$paramsDate2,$did,$branchconditions2,$carconditions);

	    return ['carmasters' => $carmasters, 'ordertrans' => $ordertrans];
	}

	/*
	 * Ga003用
	 */
	function data_select_conditionsGa003s($paramsDate,$paramsDate2,$did,$branchgroups_id) {
	    $userModel = ClassRegistry::init('Carmaster');
	    $userModel2 = ClassRegistry::init('Ordertran');
	    $userModel3 = ClassRegistry::init('Branchmaster');

	    $branchmasters = $userModel3->find('list',[
	        'fields'=>[
	            'id'
	        ],
	    ]);
		$branchconditions = [
			['Carmaster.branchmasters_id' => $branchmasters],
			['Carmaster.departmentmasters_id' => $did]
		];
		$branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters];
	    //truckkindではなく、carmaster
        $carmasters = $userModel->SelectCarmasterGa003s($paramsDate,$paramsDate2,$branchconditions);
        $ordertrans = $userModel2->SelectOrdertranGa003s($paramsDate,$paramsDate2,$did,$branchconditions2);

	    return ['carmasters' => $carmasters, 'ordertrans' => $ordertrans];
	}

	/*
	 * Co003用
	 */
	function data_select_conditionsCo503s($paramsDate,$paramsDate2,$did,$branchmaster_id) {
	    $userModel = ClassRegistry::init('Carmaster');
	    $userModel2 = ClassRegistry::init('Ordertran');

		$branchconditions = [
			['Carmaster.branchmasters_id' => $branchmasters_id],
			['Carmaster.departmentmasters_id' => $did]
		];
		$branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    //truckkindではなく、carmaster
        $carmasters = $userModel->SelectCarmasterCo003s($paramsDate,$paramsDate2,$branchconditions);
        $ordertrans = $userModel2->SelectOrdertranCo003s($paramsDate,$paramsDate2,$did,$branchconditions2);

	    return ['carmasters' => $carmasters, 'ordertrans' => $ordertrans];
	}

	function data_select_conditionsCo500s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters_id) {
	    $userModel = ClassRegistry::init('Truckkind');
	    $userModel2 = ClassRegistry::init('Ordertran');

	    if (!$shippermasters_id) {
	        $shipperconditions = [];
	        $shipperconditions2 = [];
	    } else {
	        $shipperconditions = ['Truckkind.shippermasters_id' => $shippermasters_id];
	        $shipperconditions2 = ['Ordertran.shippermasters_id' => $shippermasters_id];
	    }
	    if (!$branchmasters_id) {
	        $branchconditions = [
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = [];
	    } else {
	        $branchconditions = [
	            ['Carmaster.branchmasters_id' => $branchmasters_id],
	            ['Carmaster.departmentmasters_id' => $did]
	        ];
	        $branchconditions2 = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$shippermasters_id && !$branchmasters_id) {
	        $truckkinds = [];
	        $ordertrans = [];
	    } else {
	        $truckkinds = $userModel->SelectTruckkindCo500sTest($paramsDate,$paramsDate2,$shipperconditions,$branchconditions);
	        $ordertrans = $userModel2->SelectOrdertranCo500s($paramsDate,$paramsDate2,$did,$shipperconditions2,$branchconditions2);
	    }

	    return ['truckkinds' => $truckkinds, 'ordertrans' => $ordertrans];
	}
	/*
	 * 日付・営業所のセレクトによるデータ切り替え（020s系列用）
	 */
	function data_select_conditions020s($paramsDate,$branchmasters_id,$did) {
		$userModel = ClassRegistry::init('Truckkind');

		if (!$branchmasters_id) {
			$truckkinds = [];
		} else {
			$truckkinds = $userModel->SelectTruckkind020s($paramsDate,$branchmasters_id,$did);
		}

		return $truckkinds;
	}

	/*
	 * 日付・営業所のセレクトによるデータ切り替え（030s系列用）
	 */
	function data_select_conditions030s($paramsDate,$did,$branchmasters_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');

	    if (!$branchmasters_id) {
	        $branchconditions = ['Ordertran.branchmasters_id' => 0];
	    } else {
	        $branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    }
	    $ordertrans = $userModel->SelectOrdertran030s($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditionsch030s($paramsDate,$did,$branchmasters_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		$branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    $ordertrans = $userModel->SelectOrdertranch030s($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditionsch030nis($paramsDate,$did,$branchgroups_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		//branchmasterのIDのみ取得
		$branchgroups = $userModel2->Branchgrouplist($kaisyaid,$branchgroups_id);
		//配列を作成
		$branccond = '';
		if($branchgroups){
			$w_branchcond = array_values($branchgroups);
			$branchconditions = ['Ordertran.branchmasters_id' => $w_branchcond];
		}else{
			$branchconditions = ['Ordertran.branchmasters_id' => 0];
		}
	    $ordertrans = $userModel->SelectOrdertranch030nis($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}
	function data_select_conditionsco030nis($paramsDate,$did,$branchgroups_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		//branchmasterのIDのみ取得
		$branchgroups = $userModel2->Branchgrouplist($kaisyaid,$branchgroups_id);
		//配列を作成
		$branccond = '';
		if($branchgroups){
			$w_branchcond = array_values($branchgroups);
			$branchconditions = ['Ordertran.branchmasters_id' => $w_branchcond];
		}else{
			$branchconditions = ['Ordertran.branchmasters_id' => 0];
		}
	    $ordertrans = $userModel->SelectOrdertranco030nis($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditionsco030s($paramsDate,$did,$branchmasters_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		$branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    $ordertrans = $userModel->SelectOrdertranco030s($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}


	function data_select_conditionsch030bes($paramsDate,$did,$branchgroups_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		//branchmasterのIDのみ取得
		$branchgroups = $userModel2->Branchgrouplist($kaisyaid,$branchgroups_id);
		//配列を作成
		$branccond = '';
		if($branchgroups){
			$w_branchcond = array_values($branchgroups);
			$branchconditions = ['Ordertran.branchmasters_id' => $w_branchcond];
		}else{
			$branchconditions = ['Ordertran.branchmasters_id' => 0];
		}
	    //$ordertrans = $userModel->SelectOrdertran030bes($paramsDate,$did,$branchconditions);
	    $ordertrans = $userModel->SelectOrdertranch030bes($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditionsco030bes($paramsDate,$did,$branchgroups_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		//branchmasterのIDのみ取得
		$branchgroups = $userModel2->Branchgrouplist($kaisyaid,$branchgroups_id);
		//配列を作成
		$branccond = '';
		if($branchgroups){
			$w_branchcond = array_values($branchgroups);
			$branchconditions = ['Ordertran.branchmasters_id' => $w_branchcond];
		}else{
			$branchconditions = ['Ordertran.branchmasters_id' => 0];
		}
	    //$ordertrans = $userModel->SelectOrdertran030bes($paramsDate,$did,$branchconditions);
	    $ordertrans = $userModel->SelectOrdertranco030bes($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditions030containers($paramsDate,$did,$branchmasters_id,$kaisyaid) {
		//2022-08-12 branchmasters_idはbranchgroups_idが入っている
	    $userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Branchmaster');
		//branchmasterのIDのみ取得
		$branchgroups = $userModel2->Branchgrouplist($kaisyaid,$branchmasters_id);
		//配列を作成
		$i=0;
		foreach($branchgroups as $aa){
			if ($i==0){
				$branchcond = $aa;
			}else{
				$branchcond = $branchcond.','.$aa;
			}
			$i++;
		}
		//3,4,34,35 と入っているはず
		/* 2022-08-12
	    if (!$branchmasters_id) {
	        $branchconditions = ['Ordertran.branchmasters_id' => 0];
	    } else {
	        $branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    }
		*/
		if($branchcond){
			$branchconditions = ['Ordertran.branchmasters_id' => $branchcond];
		}else{
			$branchconditions = ['Ordertran.branchmasters_id' => 0];
		}

	    $ordertrans = $userModel->SelectOrdertran030containers($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	function data_select_conditions530s($paramsDate,$did,$branchmasters_id) {
	    $userModel = ClassRegistry::init('Ordertran');

	    if (!$branchmasters_id) {
	        $branchconditions = ['Ordertran.branchmasters_id' => 0];
	    } else {
	        $branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
	    }
	    $ordertrans = $userModel->SelectOrdertran530s($paramsDate,$did,$branchconditions);

	    return $ordertrans;
	}

	/*
	 * 日付・荷主・実績のセレクトによるデータ切り替え（080s系列用）
	 */

	function data_select_conditions080s($paramsDate,$paramsDate2,$did,$shippermasters_id,$flg,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if ($flg == null) {
			$flgconditions = [];
		} else {
			$flgconditions = ['Ordertran.correctflg' => $flg];
		}
		$ordertrans = $userModel->SelectOrdertran080s($paramsDate,$paramsDate2,$did,$shipperconditions,$flgconditions,$branchmasters_id);

		return $ordertrans;
	}
	// cidを追加（車両）
	function data_select_conditions080_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$cid) {
			$cidconditions = [];
		} else {
			$cidconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if(($did==4)or($did==7)){
			$ordertrans = $userModel->SelectOrdertran080_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}else{
			$ordertrans = $userModel->SelectOrdertran080_index($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}

		return $ordertrans;
	}
	// 請求日を条件に。
	function data_select_conditions081_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$cid) {
			$cidconditions = [];
		} else {
			$cidconditions = ['Ordertran.carmasters_id' => $cid];
		}

		if($did==4){
			$ordertrans = $userModel->SelectOrdertran081_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}else{
			$ordertrans = $userModel->SelectOrdertran081_index($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}

		return $ordertrans;
	}
	//ガス用
	function data_select_conditionsGa081_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid,$tansanflg) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$cid) {
			$cidconditions = [];
		} else {
			$cidconditions = ['Ordertran.carmasters_id' => $cid];
		}

		$ordertrans = $userModel->SelectOrdertranGa081_index($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions,$tansanflg);

		return $ordertrans;
	}

	// 請求日を条件に(csv用）
	function data_select_conditions081_csv($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$cid) {
			$cidconditions = [];
		} else {
			$cidconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if(($did == 4)or($did == 7)){
			$ordertrans = $userModel->SelectOrdertran081_csv_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}else{
			$ordertrans = $userModel->SelectOrdertran081_csv($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		}

		return $ordertrans;
	}
	// 請求日を条件に(ordercar用）
	function data_select_conditions081_ordercar($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid) {
		$userModelA = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if (!$cid) {
			$cidconditions = [];
		} else {
			$cidconditions = ['Ordertran.carmasters_id' => $cid];
		}


		$ordertrans = $userModelA->SelectOrdertran081_ordercar_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions);
		return $ordertrans;

	}


	function data_select_conditions200_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$cid,$branchgroups_id) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if(!$cid){
			$carconditions = [];
		}else{
			$carconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if (!$branchgroups_id) {
			$branchmasterconditions = [];
		} else {
			//branchgroupsからbranchmasters_idを取得
			$branchmasters = ClassRegistry::init('Branchmaster');
			$bm = $branchmasters->find('list', [
				'fields'=>[
					'id'
				],
				'conditions'=>[
					'branchgroups_id'=>$branchgroups_id
				],
				'order' => ['id' => 'asc']
			]);
			$branchmasterconditions = ['Ordertran.branchmasters_id' => $bm];
		}
		if($did==4){
			$ordertrans = $userModel->SelectOrdertran200_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$carconditions,$branchmasterconditions);
		}else{
			$ordertrans = $userModel->SelectOrdertran200_index_chemi($paramsDate,$paramsDate2,$did,$shipperconditions,$carconditions,$branchmasterconditions);
		}

		return $ordertrans;
	}
	//ガス用
	function data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$cid,$tansanflg) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if(!$cid){
			$carconditions = [];
		}else{
			$carconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if(!$tansanflg){
			$tansanconditions = ['Carmaster.tansanflg' => '0'];
		}else{
			$tansanconditions = ['Carmaster.tansanflg' => $tansanflg];
		}
		$ordertrans = $userModel->SelectOrdertranGa200_index_gas($paramsDate,$paramsDate2,$did,$shipperconditions,$carconditions,$tansanconditions);
		return $ordertrans;
	}

	// グループ合計取得用(Ga200系)
	function data_select_conditionsGa200_index_sum($paramsDate,$paramsDate2,$did,$shippermasters_id,$cid,$branchgroups_id,$tansanflg) {
		$userModel = ClassRegistry::init('Ordertran');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if(!$cid){
			$carconditions = [];
		}else{
			$carconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if (!$branchgroups_id) {
			$branchmasterconditions = [];
		} else {
			//branchgroupsからbranchmasters_idを取得
			$branchmasters = ClassRegistry::init('Branchmaster');
			$bm = $branchmasters->find('list', [
				'fields'=>[
					'id'
				],
				'conditions'=>[
					'branchgroups_id'=>$branchgroups_id
				],
				'order' => ['id' => 'asc']
			]);
			$branchmasterconditions = ['Ordertran.branchmasters_id' => $bm];
		}
		if($tansanflg){
			$tansanconditions = ['Carmaster.tansanflg' => $tansanflg];
		}else{
			$tansanconditions = ['Carmaster.tansanflg' => '0'];
		}
		$ordertrans_sum = $userModel->SelectOrdertranGa200_index_chemi_sum($paramsDate,$paramsDate2,$did,$shipperconditions,$carconditions,$branchmasterconditions,$tansanconditions);

		return $ordertrans_sum;
	}
	/*
	 * 日付・営業所のセレクトによるデータ切り替え（090s系列用）
	 */
	function data_select_conditions090s($paramsDate,$did,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('User');

		if (!$branchmasters_id) {
			$branchconditions = [];
			$userlist = $userModel2->SelectUserDriversList($did);
		} else {
			$branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
			$userlist = $userModel2->SelectUserDriversList2($did,$branchmasters_id);
		}
		$ordertrans = $userModel->SelectOrdertran090s($paramsDate,$did,$branchconditions);
		$multilist = $userModel2->SelectUserMultiDriversList();
		$list = array_diff($multilist,$userlist);
		$list2 = ($userlist + $list);

		//return $ordertrans;
		return ['ordertrans' => $ordertrans, 'userlist' => $list2];
	}

	/*
	 * 日付・営業所のセレクトによるデータ切り替え（091s系列用）
	 */
	function data_select_conditions091s($paramsDate,$did,$branchgroups_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel3 = ClassRegistry::init('Branchmaster');
        $branchmasters = $userModel3->find('list',[
            'fields'=>[
                'id'
            ],
            'conditions'=>[
                'branchgroups_id'=>$branchgroups_id
            ]
        ]);
		$branchmasterconditions = ['Ordertran.branchmasters_id' => $branchmasters];

		$ordertrans = $userModel->SelectOrdertran091s($paramsDate,$did,$branchmasterconditions);

		return $ordertrans;
	}

	/*
	 * 日付・部署・営業所のセレクトによるデータ切り替え（Host010s/040s系列用)
	 */
	/*
	function userdata_select_conditions($paramsDate,$paramsDate2,$departmentmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('User');

		if (!$branchmasters_id && !$departmentmasters_id) {
			$users = $userModel->SelectUserData($paramsDate,$paramsDate2);
		} elseif (!$branchmasters_id) {
			$users = $userModel->SelectUserDepartment($paramsDate,$paramsDate2,$departmentmasters_id);
		} elseif (!$departmentmasters_id) {
			$users = $userModel->SelectUserBranch($paramsDate,$paramsDate2,$branchmasters_id);
		} else {
			$users = $userModel->SelectUserAll($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id);
		}

		return $users;
	}
	*/

	/*
	 * 日付・荷主・商品・車両のセレクトによるデータ切り替え（100s系列用）
	 */
	function data_select_conditions100s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters = null) {
	    $userModel = ClassRegistry::init('Invoice');

	    if (!$shippermasters_id) {
	        $shipperconditions = [];
	    } else {
	        $shipperconditions = ['Invoice.shippermasters_id' => $shippermasters_id];
	    }
	    if (!$branchmasters) {
	        $branchconditions = [];
	    } else {
	        $branchconditions = ['Invoice.branchmasters_id' => $branchmasters];
	    }
		
	    $invoices = $userModel->SelectInvoice100s($paramsDate,$paramsDate2,$did,$shipperconditions,$branchconditions);

	    return ['invoices' => $invoices];
	}
	function data_select_conditions140s2($paramsDate,$paramsDate2,$did,$shippermasters_id) {
		$userModel = ClassRegistry::init('Invoice');

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Invoice.shippermasters_id' => $shippermasters_id];
		}
		$invoices = $userModel->SelectInvoice140s2($paramsDate,$paramsDate2,$did,$shipperconditions);

		return ['invoices' => $invoices];
	}
/*
	 * 日付・荷主・商品・車両のセレクトによるデータ切り替え（110s系列用）
	 */
	function data_select_conditions110s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Productdetailmaster');

		$productdetailmasters = $userModel2->pdnamelist($did);

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		}
		if (!$productdetailmasters_id) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $productdetailmasters_id];
		}
		if (!$carmasters_id) {
			$carconditions = ['Ordertran.carmasters_id' => 0]; // 車両を選択しないと明細を表示しない
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $carmasters_id];
		}

		$ordertrans = $userModel->SelectOrdertran110s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);

		return ['ordertrans' => $ordertrans, 'pdlist' => $productdetailmasters];
	}

	function data_select_conditions110s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');

		if ($shippermasters_id == 0) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if ($productdetailmasters_id == 0) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $productdetailmasters_id];
		}
		if ($carmasters_id == 0) {
			$carconditions = [];
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $carmasters_id];
		}

		$ordertrans = $userModel->SelectOrdertranPricemaster110s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
//		$ordertrans = $userModel->SelectOrdertran110s2($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions);
		$orderlist = $userModel->SelectOrdertran110s3($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
		return ['ordertrans' => $ordertrans, 'orderlist' => $orderlist];
	}

	/*
	 * 日付・荷主・商品・車両のセレクトによるデータ切り替え（120s系列用）
	 */
	function data_select_conditions120s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Productdetailmaster');

		$productdetailmasters = $userModel2->pdnamelist($did);


		if (!$productdetailmasters_id) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $productdetailmasters_id];
		}
		if (!$carmasters_id) {
			$carconditions = [];
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $carmasters_id];
		}
		if (!$shippermasters_id) {
			//$shipperconditions = [];
			$ordertrans = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
			$ordertrans = $userModel->SelectOrdertran120s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
		}

		return ['ordertrans' => $ordertrans, 'pdlist' => $productdetailmasters];
	}
	/*
	 * 日付・荷主のセレクトによるデータ切り替え（120s系列用）鏡用
	 */
	function data_select_conditions120s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Invoice');

		if (!$shippermasters_id) {
			$invoices = [];
		} else {
			$shipperconditions = ['Invoice.shippermasters_id' => $shippermasters_id];
			$invoices = $userModel->SelectInvoice120s2($paramsDate,$paramsDate2,$did,$shipperconditions,$branchmasters_id);
		}

		return $invoices;
	}

	function data_select_conditions120spdf($paramsDate,$paramsDate2,$did,$sid,$pid,$cid,$bid) {
		$userModel = ClassRegistry::init('Ordertran');

		if ($pid == 0) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $pid];
		}
		if ($cid == 0) {
			$carconditions = [];
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $cid];
		}
		if ($sid == 0) {
			//$shipperconditions = [];
			$ordertrans = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $sid];
			$ordertrans = $userModel->SelectOrdertran120spdf($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$bid);
		}
		//$ordertrans = $userModel->SelectOrdertran120spdf($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions);

		return $ordertrans;
	}

	/*
	 * 日付・荷主・商品・車両のセレクトによるデータ切り替え（130s系列用）
	 */
	function data_select_conditions130s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Productdetailmaster');

		$productdetailmasters = $userModel2->pdnamelist($did);

		if (!$shippermasters_id) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
			$productdetailmasters = $userModel2->pdnameList2($shippermasters_id);
		}
		if (!$productdetailmasters_id) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $productdetailmasters_id];
		}
		if (!$carmasters_id) {
			$carconditions = [];
			$ordertrans = [];
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $carmasters_id];
			$ordertrans = $userModel->SelectOrdertran130s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
		}

		return ['ordertrans' => $ordertrans, 'pdlist' => $productdetailmasters];
	}

	function data_select_conditions130s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id) {
		$userModel = ClassRegistry::init('Ordertran');
		$userModel2 = ClassRegistry::init('Shippermaster');

		if ($shippermasters_id == 0) {
			$shipperconditions = [];
		} else {
			$shipperconditions = ['Ordertran.shippermasters_id' => $shippermasters_id];
		}
		if ($productdetailmasters_id == 0) {
			$productconditions = [];
		} else {
			$productconditions = ['Ordertran.productdetailmasters_id' => $productdetailmasters_id];
		}
		if ($carmasters_id == 0) {
			$carconditions = [];
			$ordertrans = [];
			$orderlist = [];
		} else {
			$carconditions = ['Ordertran.carmasters_id' => $carmasters_id];
			$ordertrans = $userModel->SelectOrdertran130s2($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
			$orderlist = $userModel->SelectOrdertran130s3($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id);
		}

		//$ordertrans = $userModel->SelectOrdertran130s2($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions);
		//$orderlist = $userModel->SelectOrdertran130s3($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions);

		return ['ordertrans' => $ordertrans, 'orderlist' => $orderlist];
	}

	/*
	 * 日付・営業所によるデータの切り替え（140s系列用）
	 */
	function data_select_conditions140s($paramsDate,$paramsDate2,$did,$branchmasters_id) {
		$userModel = ClassRegistry::init('Invoice');

		if (!$branchmasters_id) {
			$branchconditions = ['Invoice.branchmasters_id' => 0];
		} else {
			$branchconditions = ['Invoice.branchmasters_id' => $branchmasters_id];
		}
		$invoices = $userModel->SelectInvoice140s($paramsDate,$paramsDate2,$did,$branchconditions);

		return $invoices;
	}

	function data_select_conditionsHost010s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyaid) {
		$userModel = ClassRegistry::init('User');

		if (!$branchmasters_id) {
			$branchconditions = [];
		} else {
			$branchconditions = ['User.branchmasters_id' => $branchmasters_id];
		}
		if (!$departmentmasters_id) {
			$departmentconditions = [];
		} else {
			$departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
		}
		$users = $userModel->SelectUserHost010s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid);

		return $users;
	}

	function data_select_conditionsHost002bs($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyaid) {
		$userModel = ClassRegistry::init('User');

		if (!$branchmasters_id) {
			$branchconditions = [];
		} else {
			$branchconditions = ['User.branchmasters_id' => $branchmasters_id];
		}
		if (!$departmentmasters_id) {
			$departmentconditions = [];
		} else {
			$departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
		}
		$users = $userModel->SelectUserHost002bs($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid);

		return $users;
	}

	function data_select_conditionsHost017s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyaid) {
		$userModel = ClassRegistry::init('User');

		if (!$branchmasters_id) {
			$branchconditions = [];
		} else {
			$branchconditions = ['User.branchmasters_id' => $branchmasters_id];
		}
		if (!$departmentmasters_id) {
			$departmentconditions = [];
		} else {
			$departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
		}
		$users = $userModel->SelectUserHost017s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid);

		return $users;
	}

	function data_select_conditionsHost050s($paramsDate,$branchmasters_id,$departmentmasters_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('User');

	    if (!$branchmasters_id) {
	        $branchconditions = [];
	    } else {
	        $branchconditions = ['User.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$departmentmasters_id) {
	        $departmentconditions = [];
	    } else {
	        $departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
	    }
	    $users = $userModel->SelectUserHost050s($paramsDate,$branchconditions,$departmentconditions,$kaisyaid);

	    return $users;
	}
	function data_select_conditionsHost060s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyaid) {
	    $userModel = ClassRegistry::init('User');

	    if (!$branchmasters_id) {
	        $branchconditions = [];
	    } else {
	        $branchconditions = ['User.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$departmentmasters_id) {
	        $departmentconditions = [];
	    } else {
	        $departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
	    }
	    $users = $userModel->SelectUserHost060s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid);

	    return $users;
	}

	function data_select_conditionsHost061s($paramsDate,$kaisyaid) {
	    $userModel = ClassRegistry::init('User');

	    $users = $userModel->SelectUserHost061s($paramsDate,$kaisyaid);

	    return $users;
	}

	function data_select_conditionsHost012s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyas_id,$flg) {
		$userModel = ClassRegistry::init('User');

		if (!$branchmasters_id) {
			/* 本社所属以外の社員が営業所のセレクトで「未選択」を選んだときの処理
			 * $flgが1のときは本社所属なので「未選択」は条件なしになる（全て表示）
			 * それ以外のときは自身の所属以外のデータを表示しない
			 */
            if ($flg == 1) {
                $branchconditions = [];
            } else {
				$branchconditions = ['User.branchmasters_id' => $flg];
			}
		} else {
			$branchconditions = ['User.branchmasters_id' => $branchmasters_id];
		}
		if (!$departmentmasters_id) {
			$departmentconditions = [];
		} else {
			$departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
		}
		$users = $userModel->SelectUserHost012s($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id);

		return $users;
	}

	function data_select_conditionsHost012s2($kaisyas_id,$flg) {
	    $userModel = ClassRegistry::init('User');

        if ($flg == 1) {
            $branchconditions = [];
        } else {
            $branchconditions = ['User.branchmasters_id' => $flg];
        }
        $departmentconditions = [];
	    $paramsDate1=date('Y-m-d');
	    $paramsDate2=date('Y-m-d');
	    $users = $userModel->SelectUserHost012s2($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id);

	    return $users;
	}
	function data_select_conditionsHost014s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyas_id) {
	    $userModel = ClassRegistry::init('Kintaitran');

	    if (!$branchmasters_id) {
	        /* 本社所属以外の社員が営業所のセレクトで「未選択」を選んだときの処理
	         * $flgが1のときは本社所属なので「未選択」は条件なしになる（全て表示）
	         * それ以外のときは自身の所属以外のデータを表示しない
	         */
	        if ($flg == 1) {
	            $branchconditions = [];
	        } else {
	            $branchconditions = ['User.branchmasters_id' => $flg];
	        }
	    } else {
	        $branchconditions = ['User.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$departmentmasters_id) {
	        $departmentconditions = [];
	    } else {
	        $departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
	    }
	    $users = $userModel->SelectKintaitranHost014s($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id);

	    return $users;
	}

	function data_select_conditionsHostS20s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyas_id) {
	    $userModel = ClassRegistry::init('Kintaitran');

	    if (!$branchmasters_id) {
	        /* 本社所属以外の社員が営業所のセレクトで「未選択」を選んだときの処理
	         * $flgが1のときは本社所属なので「未選択」は条件なしになる（全て表示）
	         * それ以外のときは自身の所属以外のデータを表示しない
	         */
	        if ($flg == 1) {
	            $branchconditions = [];
	        } else {
	            $branchconditions = ['User.branchmasters_id' => $flg];
	        }
	    } else {
	        $branchconditions = ['User.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$departmentmasters_id) {
	        $departmentconditions = [];
	    } else {
	        $departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
	    }
	    $users = $userModel->SelectKintaitranHostS20s($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id);

	    return $users;
	}

	function data_select_conditionsHost016s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$kaisyas_id) {
	    $userModel = ClassRegistry::init('Kintaitran');

	    if (!$branchmasters_id) {
	        /* 本社所属以外の社員が営業所のセレクトで「未選択」を選んだときの処理
	         * $flgが1のときは本社所属なので「未選択」は条件なしになる（全て表示）
	         * それ以外のときは自身の所属以外のデータを表示しない
	         */
	        if ($flg == 1) {
	            $branchconditions = [];
	        } else {
	            $branchconditions = ['User.branchmasters_id' => $flg];
	        }
	    } else {
	        $branchconditions = ['User.branchmasters_id' => $branchmasters_id];
	    }
	    if (!$departmentmasters_id) {
	        $departmentconditions = [];
	    } else {
	        $departmentconditions = ['User.departmentmasters_id' => $departmentmasters_id];
	    }
	    $users = $userModel->SelectKintaitranHost016s($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id);

	    return $users;
	}

	/*
	 * データ編集後のページ変遷処理用
	 */
	function backpage_select($id,$flg,$page) {
		if ($flg == 0) {
			$conditions = ['action' => 'index', 'controller' => $page];
		} elseif ($flg == 2) {
			$conditions = ['action' => 'index', 'controller' => $page];
		} elseif ($flg == 5) {
			$conditions = ['action' => 'add', 'controller' => $page, $id];
		}
		return $conditions;
	}

	/*
	 * 000s系列における営業所セレクトの制御処理
	 */
	function branch_select_conditions ($rolemasters_id,$branchmasters_id,$kaisyas_id) {
		$userModel = ClassRegistry::init('Branchmaster');
		if ($rolemasters_id == 10) { // 管理者は所属に関係なし
			$branchmasters = $userModel->BranchList($kaisyas_id);
		} else {
			switch ($branchmasters_id) {
				case 1: // 本社所属
					$branchmasters = $userModel->BranchList($kaisyas_id);
					break;
				default: // それ以外
					$branchmasters = $userModel->BranchList2($kaisyas_id, $branchmasters_id);
					break;
			}
		}
		return $branchmasters;
	}
	/*
	 * 080s系列における営業所セレクトの制御処理
	 */
	function branchmaster_select_conditions ($branchmaster_id,$kaisyas_id,$rolemasters_id) {
		$userModel = ClassRegistry::init('Branchmaster');
		if($rolemasters_id < 20){
			$branchmasters = $userModel->BranchList4($kaisyas_id, $rolemasters_id);
		}else{
			$branchmasters = $userModel->BranchList2($kaisyas_id, $branchmaster_id);
		}
		return $branchmasters;
	}
	/*
	 * 000s系列における営業所セレクトの制御処理
	 */
	function branchgroup_select_conditions ($branchgroups_id,$kaisyas_id,$rolemasters_id) {
		$userModel = ClassRegistry::init('Branchmaster');
		if($rolemasters_id < 20){
			$branchmasters = $userModel->BranchList3($kaisyas_id, $branchgroups_id);
		}else{
			$branchmasters = $userModel->BranchList3($kaisyas_id, $branchgroups_id);
		}
		return $branchmasters;
	}
	/*
	 * 勤怠用
	 */
	function branch_select_conditions_kintai ($rolemasters_id,$branchmasters_id,$kaisyas_id) {
	    $userModel = ClassRegistry::init('Branchmaster');
	    if ($rolemasters_id == 10) { // 管理者は所属に関係なし
	        $branchmasters = $userModel->BranchList_kintai($kaisyas_id);
	    } else {
	        switch ($branchmasters_id) {
	            case 1: // 本社所属
	                $branchmasters = $userModel->BranchList_kintai($kaisyas_id);
	                break;
				case 3: //川崎
				case 4: //袖ケ浦
					$branchmasters = $userModel->BranchList_kanto($kaisyas_id,$branchmasters_id);
					break;
	            default: // それ以外
					$branchmasters = $userModel->BranchList2($kaisyas_id, $branchmasters_id);
	                break;
	        }
	    }
	    return $branchmasters;
	}
	/*
	 * 003s系列における営業所セレクトの制御処理
	 */
	function branch_select003_conditions ($branchmasters_id,$kaisyas_id) {
	    $userModel = ClassRegistry::init('Branchmaster');
        switch ($branchmasters_id) {
            case 1: // 本社所属
                $branchmasters = $userModel->BranchList($kaisyas_id);
                break;
            default: // それ以外
                $branchmasters = $userModel->BranchList2($kaisyas_id, $branchmasters_id);
                break;
        }
	    return $branchmasters;
	}

	function backpage_select2 ($backdata,$page) {
		if ($backdata) {
		//    $backpage = $backdata['Page'];
		    $backpage = $page;
		} else {
			$backpage = $page;
		}
		return $backpage;
	}

}
