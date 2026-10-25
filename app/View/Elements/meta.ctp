<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<?php echo $this->Html->charset();
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex'));
		echo $this->Html->meta('description','AT-system');
	?>
    <title>ATシステム</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

<?php
		//echo $this->Html->css(array('bootstrap-cerulean.min', 'charisma-app', '/bower_components/fullcalendar/dist/fullcalendar', '/bower_components/fullcalendar/dist/fullcalendar.print', '/bower_components/chosen/chosen.min', '/bower_components/colorbox/example3/colorbox', '/bower_components/responsive-tables/responsive-tables', '/bower_components/bootstrap-tour/build/css/bootstrap-tour.min', 'jquery.noty', 'noty_theme_default', 'elfinder.min', 'elfinder.theme', 'jquery.iphone.toggle', 'uploadify', 'animate.min'));

		//echo $this->Html->script(array('/bower_components/bootstrap/dist/js/bootstrap.min', '/bower_components/jquery/jquery.min', 'jquery.cookie', '/bower_components/moment/min/moment.min', '/bower_components/fullcalendar/dist/fullcalendar.min', 'jquery.dataTables.min', '/bower_components/chosen/chosen.jquery.min', '/bower_components/colorbox/jquery.colorbox-min', 'jquery.noty', '/bower_components/responsive-tables/responsive-tables', '/bower_components/bootstrap-tour/build/js/bootstrap-tour.min', 'jquery.raty.min', 'jquery.iphone.toggle', '/js/jquery.autogrow-textarea', 'jquery.uploadify-3.1.min', 'jquery.history', 'charisma'));
?>

<?php
	echo $this->Html->css([
		// jQuery
		//'//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/pepper-grinder/jquery-ui.css',
		'jquery-ui.min.css',
		'jquery-ui.structure.min.css',
		'jquery-ui.theme.min.css',

		// DateTimepicker
		'jquery.datetimepicker.css',

		// Timepicker
		'jquery.timepicker.min.css',

		// FullCalendar
		'fullcalendar.min.css',

		// デザインCSS
		/* それぞれLayoutファイルからCSSを指定　変数で受け取っている */
		$ThemeCss,
		/**
		 * 管理者画面
		 * 'bootstrap-united.min',
		 * 事務職画面
		 * 'bootstrap-cerulean.min',
		 * 協力会社画面
		 * 'bootstrap-simplex.min',
		 * ドライバー画面
		 * 'bootstrap-spacelab.min',
		 */
		'charisma-app',

		'/bower_components/chosen/chosen.min',
		'/bower_components/colorbox/example3/colorbox',
		'elfinder.min',
		'elfinder.theme',
		'jquery.iphone.toggle',
		'uploadify',
		'animate.min',
		'error.css'
		/** ここから不要であるもの思われるCSS
		* '/bower_components/fullcalendar/dist/fullcalendar',
		* '/bower_components/fullcalendar/dist/fullcalendar.print',
		* '/bower_components/responsive-tables/responsive-tables',
		* '/bower_components/bootstrap-tour/build/css/bootstrap-tour.min',
		* 'jquery.noty',
		* 'noty_theme_default',
		*/

	]);

	echo $this->Html->script([
		// jQuery
		'jquery-3.3.1.min.js',
		'jquery-ui.min.js',
		'datepicker-ja.js',
		/**
		* '//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		* '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
		* '//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery-ui-i18n.min.js',
		*/

		// Datepicker
		'jQuery.datepicker.js',

		// DateTimepicker
		'jquery.datetimepicker.full.js',

		// Timepicker
		'jquery.timepicker.min.js',
		'timepicker.js',
		'timepicker2.js?v=1',

		// FullCalendar
		'moment.min.js',
//		'fullcalendar.min.js',
		'ja.js',
		'redips-drag-min.js',

		'jquery.cookie',
		'/bower_components/chosen/chosen.jquery.min',
		'/bower_components/colorbox/jquery.colorbox-min',
		'/bower_components/bootstrap/dist/js/bootstrap.min',
		'jquery.iphone.toggle',
		'/js/jquery.autogrow-textarea',
		'jquery.uploadify-3.1.min',
		'jquery.history',
		'charisma',

		/** ここから不要であるものと思われるjs
		* 'bootstrap.min.js',
		* 'jquery.noty',
		* '/bower_components/jquery/jquery.min',
		* '/bower_components/moment/min/moment.min',
		* '/bower_components/fullcalendar/dist/fullcalendar.min',
		* 'jquery.dataTables.min',
		* '/bower_components/responsive-tables/responsive-tables',
		* '/bower_components/bootstrap-tour/build/js/bootstrap-tour.min',
		* 'jquery.raty.min',
		*/

	]);
?>
<link rel="icon" href="/favicon.ico" id="favicon">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script> -->
</head>
