<?php
App::uses('AppModel', 'Model');
//App::uses('MyUploadBehavior', 'Model/Behavior');

/**
 * Page Model
 *
 */
class Loadingmasterimage extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Loadingmasterimage';
	public $useTable = 'loadingmasterimages';

	var $actsAs = [
		'UploadPack.Upload' => [
			'img' => [
				/*
				:app – CakePHPのappフォルダ
				:webroot – webrootフォルダ
				:model – テーブルと同じ名称
				:id – id
				:basename – ファイルの元ファイル名
				:extension – ファイルの元ファイルの拡張子にあたる部分
				:style – thumbnailなど、任意の名称。
				:attachment – フィールド名と同じ部分で今回だと「img」
				:hash – 元ファイル名をMD5でハッシュ化+Security.salt
				*/
				'path' => ':webroot/loadingimages/:style/:id.:extension',
				'quality' => 50,
				'styles' => [
					'resize' => '1200l'
				]
			]
		],
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public $validate = [
		'img' => [
			'image' => [
				'rule' => ['attachmentContentType', ['image/jpeg']],
				'message' => '画像形式はjpgのものをアップロードしてください。'
			],
			/*
			'maxSize' => [
				'rule' => ['attachmentMaxSize', 2097152],
				'message' => '2MB以上を越えています'
			]
			*/
		],
	];

	public $belongsTo = [
		'Loadingmaster' => [
			'className' => 'Loadingmaster',
			'foreignKey' => 'loadingmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];

}
