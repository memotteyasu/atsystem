<?php
App::uses('AppController', 'Controller');

class FileUpComponent extends Component{

	public function file_paht($id) {
		$Image = ClassRegistry::init('Image');
		$cont_id = sprintf("%08d", $id);
		$dir1 = substr($cont_id, 0, 2); //0埋めしたIDをスライスして、ディレクトリID　1000ファイルごとにディレクトリ分散
		$dir2 = substr($cont_id, 2, 3); //一応これで、99999999以上は余裕でいけます。
		$MKDir1 = Router::url('/app/webroot/photo/', true).$dir1;
		$MKDir2 = Router::url('/app/webroot/photo/', true).$dir1.'/'.$dir2;
		//画像保存ディレクトリの生成
		if(!file_exists($MKDir1)){
			mkdir($MKDir1, 0777);
		}
		//
		//画像保存ディレクトリの生成
		if(!file_exists($MKDir2)){
			mkdir($MKDir2, 0777);
		}
		//移動させたいパス。リサイズファイルをそれぞれ指定
		$file_thumb = 'C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/'.$dir1.'/'.$dir2.'/'.$cont_id.'thumb.jpg';
		$file_small = 'C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/'.$dir1.'/'.$dir2.'/'.$cont_id.'small.jpg';
		$file_big = 'C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/'.$dir1.'/'.$dir2.'/'.$cont_id.'big.jpg';
		$file_original = 'C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/'.$dir1.'/'.$dir2.'/'.$cont_id.'original.jpg';
		if(isset($_FILES['data']['name']['Image']['img'])){

			//ImageModelに記載したパスから、本来の移動したいパスに移動させる
			rename('C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/thumb.jpg',$file_thumb);
			rename('C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/small.jpg',$file_small);
			rename('C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/big.jpg',$file_big);
			rename('C:/xampp/htdocs/Dropbox/Public/htdocs/cake_test2_2/app/webroot/img/original.jpg',$file_original);
			$Image->saveField('img_file_path', '/img/'.$dir1.'/'.$dir2.'/'.$cont_id);//出力用に、保存したファイルの場所
		}
	}

}
