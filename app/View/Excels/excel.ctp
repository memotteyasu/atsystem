<?php
// Excel出力用ライブラリ
App::import( 'Vendor', 'PHPExcel', array('file'=>'phpexcel' . DS . 'PHPExcel.php') );
App::import( 'Vendor', 'PHPExcel_IOFactory', array('file'=>'phpexcel' . DS . 'PHPExcel' . DS . 'IOFactory.php') );
App::import( 'Vendor', 'PHPExcel_Cell_AdvancedValueBinder', array('file'=>'phpexcel' . DS . 'PHPExcel' . DS . 'Cell' . DS . 'AdvancedValueBinder.php') );
// Excel95用ライブラリ
App::import( 'Vendor', 'PHPExcel_Writer_Excel5', array('file'=>'phpexcel' . DS . 'PHPExcel' . DS . 'Writer' . DS . 'Excel5.php') );
App::import( 'Vendor', 'PHPExcel_Reader_Excel5', array('file'=>'phpexcel' . DS . 'PHPExcel' . DS . 'Reader' . DS . 'Excel5.php') );

// ファイルはxls形式を使用
// テンプレートファイルの読み込み
$tmp_excel_path = realpath(TMP) . DS . 'excel' . DS;

$objReader = PHPExcel_IOFactory::createReader("Excel5");

$template_path = $tmp_excel_path .  "template.xls";
$PHPExcel = $objReader->load($template_path);

//シートの設定
$PHPExcel->setActiveSheetIndex(0);  //0はsheet1(一番左のシート)
$sheet = $PHPExcel->getActiveSheet();

// Controllerで取得したUserデータを書き出し
$Row = 2;
//$Column = 0;
foreach ($datas as $data){
	$sheet->setCellValueByColumnAndRow(0,$Row,$data['Ordertran']['productnote']);
	$sheet->setCellValueByColumnAndRow(1,$Row,$data['Shippermaster']['shippershortname']);
	$sheet->setCellValueByColumnAndRow(2,$Row,$data['Productdetailmaster']['pdshortname']);
	$sheet->setCellValueByColumnAndRow(3,$Row,$data['Deliverymaster']['deliveryshortname']);
	$sheet->setCellValueByColumnAndRow(4,$Row,$data['Loadingmaster']['loadingshortname']);

	$Row = $Row + 1;
}

//保存ファイル名
$filename = 'エクセル.xls';

//文字コード変換
$filename = mb_convert_encoding($filename, 'sjis', 'utf-8');
// 保存ファイルパス
$path = $tmp_excel_path . DS . $filename;

$objWriter = new PHPExcel_Writer_Excel5( $PHPExcel );   //2003形式で保存
$objWriter->save( $path );

// Excelファイルをクライアントに出力 ----------------------------
//保存をしてから出力
Configure::write('debug', 0);       // debugコードを非表示
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment;filename="' . $filename . '"');
readfile($path);
