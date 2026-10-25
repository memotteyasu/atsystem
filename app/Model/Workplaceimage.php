<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Workplaceimage extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */

	public $name = 'Workplaceimage';
	public $useTable = 'workplaceimages';

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
				'path' => ':webroot/workplaceimage/:style/:id.:extension',
				'quality' => 50,
				'styles' => [
					'resize' => '1200l'
				]
			]
		],
	];


	public $validate = [
		'img' => [
		    'extension'=>[
		        'rule'=>['extension',['jpg','jpeg','png','gif','pdf']],
		        'message'=>['拡張子エラー']
		    ],
		],
	];


	public $belongsTo = array(
		'Workplace' => array(
			'className' => 'Workplace',
			'foreignKey' => 'workplaces_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

}
