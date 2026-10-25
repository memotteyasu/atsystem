<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Image extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Image';
	public $useTable = 'images';

	var $actsAs = [
		'UploadPack.Upload' => [
			'img' => [
				'path' => ':webroot/photo/:style/:id.:extension',
				'quality' => 95,
				'styles' => [
					'thumb' => '85x85'
				]
			]
		],
	];
}