<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
//20200902 App::import('Vendor', 'AccessLogs');

class AppController extends Controller {

	public function appError($error) {
		print($error);
		// 20190624 $this->redirect(array('controller' => 'users', 'action' => 'login'));
	}

	public $components = array('RequestHandler','Paginator', 'Flash', 'Session',//'DebugKit.Toolbar',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'userModel' => 'User',
					'passwordHasher' => 'Blowfish',
					'fields' => array(
						'username' => 'username',
						'password' => 'password',
					),
				)
			),
			'loginAction' => array('controller'=>'users','action'=>'login'),
			'loginRedirect' => array('controller'=>'users','action'=>'index'),
			'logoutRedirect' => array('controller'=>'users','action'=>'login'),
			'authorize' => array('Controller')
		),
	);

	public function beforeFilter() {
		Configure::write('logged_user_id', $this->Auth->user('id'));
		$this->branchmasters_id = $this->Auth->user('branchmasters_id');
		$this->rolemasters_id = $this->Auth->user('rolemasters_id');
		$this->kaisyas_id = $this->Auth->user('kaisyas_id');
		$this->branchgroups_id = $this->Auth->user('Branchmaster.branchgroups_id');
		// log用
		/* 20200902
		$username = $this->Auth->user('username'); //会員のユーザ名を取得
		$this->access = new AccessLogs();
		$this->access->write_log($username);
		*/

		// Topへのリンクのためのrole変数格納 （使ってない？）
		$role_id = $this->Auth->user('rolemasters_id');
		if ($role_id >= 10 && $role_id <= 19) {
			$this->set('role','Host');
		} elseif ($role_id >= 20 && $role_id <= 28) {
			$this->set('role','Clerk');
		} elseif ($role_id == 29) {
		    $this->set('role','Partner');
		} elseif ($role_id >= 30 && $role_id <= 39) {
			$this->set('role','Dr');
		} elseif ($role_id >= 90) {
			$this->set('role','Rece');
		}
	}

	public $helpers = array(
    		'Session',
    		//'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
    		//'Form' => array('className' => 'TwitterBootstrap.BootstrapForm'),
    		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
	);
}
