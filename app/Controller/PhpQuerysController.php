<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'phpQuery');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class PhpQuerysController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
   // public $uses = array('Shippermaster','Departmentmaster', 'Ordertran');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
			$this->set('user',$user);
			return true;
		}
		return false;
		break;
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$url = 'https://stocks.finance.yahoo.co.jp/stocks/detail/?code=6328.T';

		//$html = file_get_contents("https://stocks.finance.yahoo.co.jp/stocks/detail/?code=6328.T");
		$dom = \phpQuery::newDocumentFile($url);

		$test = $dom['.innerDate > div > dl > dd > strong']->text();
		$array = explode("\n", $test); // とりあえず行に分割
		$array = array_map('trim', $array); // 各行にtrim()をかける
		$array = array_filter($array, 'strlen'); // 文字数が0の行を取り除く
		$array = array_values($array); // これはキーを連番に振りなおしてるだけ
// 		debug($array);

		$test2 = $dom['.innerDate > div > dl > dt']->text();
		$array2 = explode("\n", $test2); // とりあえず行に分割
		$array2 = array_map('trim', $array2); // 各行にtrim()をかける
		$array2 = array_filter($array2, 'strlen'); // 文字数が0の行を取り除く
		$array2 = array_values($array2); // これはキーを連番に振りなおしてるだけ

		$test3 = $dom['.innerDate > div > dl > dt > span']->text();
		$array3 = explode("\n", $test3); // とりあえず行に分割
		$array3 = array_map('trim', $array3); // 各行にtrim()をかける
		$array3 = array_filter($array3, 'strlen'); // 文字数が0の行を取り除く
		$array3 = array_values($array3); // これはキーを連番に振りなおしてるだけ

		$array4 = array_values(array_diff($array2, $array3));

// 		debug($array2);
// 		debug($array3);
// 		debug($array4);

		$this->set(compact('dom','array','array4'));
	}

}
