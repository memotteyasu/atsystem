<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Ordertranimage extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Ordertranimage';
	public $useTable = 'ordertranimages';

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
				'path' => ':webroot/ordertranimages/:style/:id.:extension',
				'quality' => 50,
				'styles' => [
					'resize' => '1200l'
				]
			]
		],
	];
//				'path' => ':webroot/ordertranimages/:style/:id.:extension',

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}
/*
	public $validate = [
		'img' => [
			'rule' => ['attachmentContentType', ['image/jpeg']],
			'message' => '画像形式はjpgのものをアップロードしてください。'
		],
	];
*/
	public $validate = [
	    'image'=>[
	        'notempty'=>[
	            'rule'=>[
	                'attachimentPresence'
	            ],
	            'message'=>[
	                'ファイルが選択されていませんよ。'
	            ]
	        ],
	        'filetype'=>[
	            'rule'=>[
	                'attachmentContentType',[
	                    'image/jpeg','image/gif','image/png','image/pdf'
	                ]
	            ],
	            'message'=>[
	                'ファイルの種類が適切ではありません'
	            ]
	        ]
	    ]
	];

	public $belongsTo = array(
		'Ordertran' => array(
			'className' => 'Ordertran',
			'foreignKey' => 'ordertrans_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

}
