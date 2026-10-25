<?php
	$this->Csv->addrow($th);
//テーブル読み込み
	$datacheck = 0;

	$i=0;
	foreach ($ordertrans as $ot) {
		//ordertranからordertran_pricemasterを読んでデータをセットする
		$ordertranpricemaster = ClassRegistry::init('OrdertranPricemaster');
		$ordertranpricemasters = [];
		$ordertranpricemasters = $ordertranpricemaster->find('all',[
			'fields'=>[
				'OrdertranPricemaster.ordertrans_id',
				'OrdertranPricemaster.pricemasters_id',
				'Pricemaster.pricename',
				'Pricemaster.price'
			],
			'conditions'=>[
				'OrdertranPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
				'OrdertranPricemaster.delflg' => 0
			],
			'contain'=>[
				'Pricemaster'
			],
			'recursive'=>0
		]);

		//ordertranからorderexpensestranを読んでデータをセットする
		$orderexpensestran = ClassRegistry::init('Orderexpensestran');
		$orderexpensestrans = [];
		$orderexpensestrans = $orderexpensestran->find('all',[
			'fields'=>[
				'Orderexpensestran.ordertrans_id',
				'Orderexpensestran.expensesname',
				'Orderexpensestran.expensesprice'
			],
			'conditions'=>[
				'Orderexpensestran.ordertrans_id' => $ot['Ordertran']['id'],
			],
			'recursive'=>0
		]);

		//コンテナ番号を取得
		$w_container = null;
		$w_ref = null;
		$w_pname = null;
		$w_productcontainer = null;
		$w_pdconname = null;
		foreach($ot['Processtran'] as $pt){
			if($pt['container_no']){
				$w_container = $pt['container_no'];
			}
			if($pt['ref_no']){
				$w_ref = $pt['ref_no'];
			}
			if($pt['productdetailmastername']){
				$w_pname = $pt['productdetailmastername'];
			}
			//コンテナ商品マスタから取得
			if(!$w_pdconname){
				$productcontainer = ClassRegistry::init('Productcontainer');
				$productcontainers = [];
				$productcontainers = $productcontainer->find('first',[
					'fields'=>[
						'Productcontainer.pdconname'
					],
					'conditions'=>[
						'Productcontainer.id' => $pt['productcontainers_id']
					]
				]);
				if($productcontainers){
					$w_pdconname = $productcontainers['Productcontainer']['pdconname'];
				}else{
					$w_pdconname = $w_pdconname;
				}
			}
		}
		

		//出力は価格マスタの数に合わせる
		if($ot['Ordertran']['delflg']==3){
			//キャンセル用
			foreach($ordertranpricemasters as $ops){
				$pricename = $ops['Pricemaster']['pricename'];
				$price = $ot['Ordertran']['cancelprice'];
				//請求日
				$this->Csv->addField($ot['Ordertran']['detailbilldate']);
				//担当者
				$this->Csv->addField($ot['Tantomaster']['tantoname']);
				//価格名称
				$this->Csv->addField($pricename);
				//コンテナ
				$this->Csv->addField($w_container);
				//ref
				$this->Csv->addField($w_ref);
				//品名
				$this->Csv->addField($w_pdconname);
				//価格
				$this->Csv->addField($price);
				//車両番号
				$this->Csv->addField($ot['Carmaster']['plateno']);
				//キャンセル
				$this->Csv->addField('cancel');
				$this->Csv->endRow();
			}

			foreach($orderexpensestrans as $oet){
				$expensesname = $oet['Orderexpensestran']['expensesname'];
				$expensesprice = $oet['Orderexpensestran']['expensesprice'];
				//請求日
				$this->Csv->addField($ot['Ordertran']['detailbilldate']);
				//担当者
				$this->Csv->addField($ot['Tantomaster']['tantoname']);
				//価格名称
				$this->Csv->addField($expensesname);
				//コンテナ
				$this->Csv->addField($w_container);
				//ref
				$this->Csv->addField($w_ref);
				//品名
				$this->Csv->addField($w_pdconname);
				//価格
				$this->Csv->addField($expensesprice);
				//車両番号
				$this->Csv->addField($ot['Carmaster']['plateno']);
				//キャンセル
				$this->Csv->addField('cancel');
				$this->Csv->endRow();
			}

		}else{
			foreach($ordertranpricemasters as $ops){
				$pricename = $ops['Pricemaster']['pricename'];
				$price = $ops['Pricemaster']['price'];
				//請求日
				$this->Csv->addField($ot['Ordertran']['detailbilldate']);
				//担当者
				$this->Csv->addField($ot['Tantomaster']['tantoname']);
				//価格名称
				$this->Csv->addField($pricename);
				//コンテナ
				$this->Csv->addField($w_container);
				//ref
				$this->Csv->addField($w_ref);
				//品名
				$this->Csv->addField($w_pdconname);
				//価格
				$this->Csv->addField($price);
				//車両番号
				$this->Csv->addField($ot['Carmaster']['plateno']);
				$this->Csv->addField('');
				$this->Csv->endRow();
			}

			foreach($orderexpensestrans as $oet){
				$expensesname = $oet['Orderexpensestran']['expensesname'];
				$expensesprice = $oet['Orderexpensestran']['expensesprice'];
				//請求日
				$this->Csv->addField($ot['Ordertran']['detailbilldate']);
				//担当者
				$this->Csv->addField($ot['Tantomaster']['tantoname']);
				//価格名称
				$this->Csv->addField($expensesname);
				//コンテナ
				$this->Csv->addField($w_container);
				//ref
				$this->Csv->addField($w_ref);
				//品名
				$this->Csv->addField($w_pdconname);
				//価格
				$this->Csv->addField($expensesprice);
				//車両番号
				$this->Csv->addField($ot['Carmaster']['plateno']);
				//キャンセル
				$this->Csv->addField('');
				$this->Csv->endRow();
			}

		}

	}

	$this->Csv->setFilename($filename);
	echo $this->Csv->render(true, 'sjis', 'utf-8');
?>
