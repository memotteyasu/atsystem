<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi030sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = ['Csv'];

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
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
		$this->set('csvs',$this->Csv->find('all'));
	}

	function import(){
		if (!empty($this->data)) {
			$model =& $this->Csv;
			/* @var $model result */
			$up_file = $this->data['Csv']['csvFile']['tmp_name'];
			$fileName = './csv/'.$this->data['Csv']['csvFile']['name'];
			if (is_uploaded_file($up_file)){
				move_uploaded_file($up_file, $fileName);
				$model->loadFromCSV($fileName);
				$lastID = $this->Csv->getLastInsertID();
				if ($lastID) {
					$succcessdate = $this->Csv->findById($lastID,'processdate');
					$succcessdate2 = New DateTime($succcessdate['Csv']['processdate']);
					$this->Flash->success($succcessdate2->format('Y年m月d日').'分までアップロードしました。');
				} else {
					$this->Flash->error('新規データが存在しません。');
				}
				$this->redirect(['action'=>'index']);
			} else {
				$this->redirect(['action'=>'index']);
			}
		}
	}

	/*
	public function import(){
		$filePath = "./csv/" . $_FILES["csvFile"]["name"];
		if (move_uploaded_file($_FILES["csvFile"]["tmp_name"], $filePath))
		{
			chmod($filePath, 0644); // ファイルアップロード成功
		}else{
			// ファイルアップロード失敗
		}

		$objFile = new SplFileObject($filePath);
		$objFile->setFlags(SplFileObject::READ_CSV);
		$objFile->setCsvControl("\t" /* 区切り文字 *//*, "\"" /* 囲い文字 *//*);

		/*
		foreach ($objFile as $key => $line)
		{
			foreach ($line as $buf)
			{
				$buf = mb_convert_encoding($buf, "UTF-8", "auto");
				$records[$key][] = $buf;
			}
		}
		echo "<pre>";
		print_r($records);
		echo "</pre>";
		foreach (glob("./csv/*.csv") as $delFile) unlink($delFile); // ファイル削除処理
	}
	*/

}
