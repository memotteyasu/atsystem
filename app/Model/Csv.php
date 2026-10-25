<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Csv extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Csv';
	public $useTable = 'csvs';

	function loadFromCSV($fileName) {
		$csvs = $this->find('all');
		$i = 0;
		$this->begin();
		try{
			//$this->deleteAll('1 = 1', false);
			$csvData = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			//CSVを一行ずつ読み込み配列へと変換
			foreach($csvData as $line){
				//CSVの一行目は項目名となるため、項目名は登録しない処理
				if ($i != 0) {
					$record = explode(",", $line);
					//日付の形式をDB側と合わせるための処理
					$processdate = new DateTime($record[14]);
					$startdate = new DateTime($record[15]);
					$enddate = new DateTime($record[16]);
					$data = [
						"honsya" => $record[0],
						"honsya_id" => $record[1],
						"areaname" => $record[2],
						"areamasters_id" => $record[3],
						"departmentname" => $record[4],
						"departmentmasters_id" => $record[5],
						"brunchname" => $record[6],
						"branchmasters_id" => $record[7],
						"username" => $record[8],
						"users_id" => $record[9],
						"carno" => $record[10],
						"datateccarid" => $record[11],
						"trailerkind" => $record[12],
						"suffixnumber" => $record[13],
						//"date" => $record[14],
						//"startdate" => $record[15],
						//"enddate" => $record[16],
						"processdate" => $processdate->format('Y-m-d'),
						"startdate" => $startdate->format('Y-m-d H:i:s'),
						"enddate" => $enddate->format('Y-m-d H:i:s'),
						"mileage" => $record[17],
						"traveltime" => $record[18],
						"drivingtime" => $record[19],
						"longdrivingtime" => $record[20],
						"generalaveragespeed" => $record[21],
						"highwayaveragespeed" => $record[22],
						"generalmaximumspeed" => $record[23],
						"highwaymaximumspeed" => $record[24],
						"idling" => $record[25],
						"accelerator" => $record[26],
						"fuelcost" => $record[27],
					];
					/*
					 * CSVテーブルが空の場合は比較処理を行わない
					 * そうでない場合は一行ずつ比較し、重複チェックを行っている
					 * 重複した場合は$dataにnullを入れ、登録しないようにしている
					 */
					// if ($csvs != null) {
					// 	foreach ($csvs as $csv) {
					// 		//debug ($data);
					// 		//debug ($csv);
					// 		//if ($csv['Csv']['honsya_id'] == $data['honsya_id'] && $csv['Csv']['areamasters_id'] == $data['areamasters_id'] && $csv['Csv']['users_id'] == $data['users_id'] && $csv['Csv']['carmasters_id'] == $data['carmasters_id'] && $csv['Csv']['suffixnumber'] == $data['suffixnumber'] && $csv['Csv']['processdate'] == $data['processdate']) {
					// 		if ($csv['Csv']['honsya_id'] == $data['honsya_id'] && $csv['Csv']['areamasters_id'] == $data['areamasters_id'] && $csv['Csv']['users_id'] == $data['users_id'] && $csv['Csv']['datateccarid'] == $data['datateccarid'] && $csv['Csv']['suffixnumber'] == $data['suffixnumber'] && $csv['Csv']['processdate'] == $data['processdate']) {
					// 			$data = null;
					// 		}
					// 	}
					// }
					$this->create();
					$this->save($data);
				}
				$i = $i + 1;
			}
			$this->commit();
		} catch(Exception $e) {
			$this->rollback();
		}
		//インポート作業が終了したら、アップロードしたCSVファイルを削除する
		unlink($fileName);
	}

/**
 * Validation rules
 *
 * @var array
 */

	/*
	public $belongsTo = array(
		'Productdetailmaster' => array(
			'className' => 'Productdetailmaster',
			'foreignKey' => 'productdetailmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Shippermaster' => [
			'className' => 'Shippermaster',
			'foreignKey' => 'shippermasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Carmaster' => [
			'className' => 'Carmaster',
			'foreignKey' => 'carmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	);
	*/

}
