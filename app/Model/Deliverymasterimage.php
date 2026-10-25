<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Deliverymasterimage extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Deliverymasterimage';
	public $useTable = 'deliverymasterimages';

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
				'path' => ':webroot/deliveryimages/:style/:id.:extension',
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
			'rule' => ['attachmentContentType', ['image/jpeg']],
			'message' => '画像形式はjpgのものをアップロードしてください。'
		],
	];


	public $belongsTo = array(
		'Deliverymaster' => array(
			'className' => 'Deliverymaster',
			'foreignKey' => 'deliverymasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

}
