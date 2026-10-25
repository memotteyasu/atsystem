<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('運行日程');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('asahi');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// ---------------------------------------------------------

	// 印刷日時を取得
	$printday = new DateTime();

	// 日付の取得
	$today = new DateTime($date);

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// A4(210*297)

	// 表面

	// 表の縦横の設定
	$col1 = 35; //30
	$col2 = 20;
	$col3 = 20; //20
	$col4 = 40; //35
	$col5 = 25;
	$col6 = 34;
	$col7 = 30; //25
	$col8 = 45;
	$col9 = 75;
	$col10 = 10;
	$col11 = 11;
	$col12 = 60;
	$col13 = 28;
	$col14 = 33;
	$col15 = 16;

	$row1 = 5;
	$row2 = 8;
	$row3 = 12;
	$row4 = 10;

	$count = 0;

			$y_line=127;
			$count = 0;
			$pdf->AddPage();

			// フォントの設定
			$pdf->SetFont('kozminproregular', '', 16);

			$pdf->MultiCell(200, 8, '朝日運輸株式会社　運転日報（部門）ケミカル・コンテナ・石油', '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
			$pdf->Line(9, 18, 178, 18);

			$pdf->SetFontSize('12');
			// $pdf->MultiCell('', '', '作成日：'.$today->format('Y年m月d日').'('.$week[$today->format('w')].')', '0', 'R', 0, 1, 0, 5, false, 0, false, true, 8, 'B');

			$pdf->Ln(7);
			// ここから日報入力欄
			$pdf->SetFontSize('18');
			$order_name = '車両：';
			$pdf->MultiCell('200', '10', $order_name, '1', 'L', 0, 1, '', 20, true, 0, false, true, '', 'M');

			//
			$pdf->SetFontSize('10');
			$pdf->MultiCell(30, 5, '運行管理印', '1', 'C', 0, 1, 255, 15, true, 0, false, true, 5, 'M');
			$pdf->MultiCell(15, 12, '', '1', 'C', 0, 0, 255, '', true, 0, false, true, 15, 'M');
			$pdf->MultiCell(15, 12, '', '1', 'C', 0, 0, '', '', true, 0, false, true, 15, 'M');

			$pdf->Ln(13);

			$pdf->SetFontSize('10');
			$pdf->MultiCell($col10, $row1, 'No', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col12, $row1, '運行日', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col13, $row1, '出発時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col13, $row1, '終了時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col13, $row1, '走行時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col13, $row1, '作業時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col13, $row1, '待機時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col14, $row1, '走行開始km', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col14-1, $row1, '走行終了km', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
			for ($i = 1; $i <= 3; $i++) {
				$pdf->MultiCell($col10, $row2, $i, '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col12, $row2, '20　　　年　　　月　　　日', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col13, $row2, '  　　：　　　', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col13, $row2, '  　　：　　　', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col13, $row2, '  　　：　　　', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col13, $row2, '  　　：　　　', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col13, $row2, '  　　：　　　', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col14, $row2, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col14-1, $row2, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			}

			$pdf->Ln(2);

			$pdf->MultiCell($col6, $row1, '高速代', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col1, $row1, '円', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col6, $row1, '現金支払い', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col1, $row1, '円', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col6, $row1, '軽油（自家燃）', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col1, $row1, '㍑', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col6, $row1, '軽油（SS）', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col6, $row1, '㍑', '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');

			$pdf->SetFontSize('8');
			$pdf->MultiCell(240, $row1, '※該当する項目に○印をつけてください。　※フェノールは回数を記入してください。　※その他作業の項目は事務員が使用します。', '0', 'L', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			$pdf->SetFontSize('9');

			$pdf->MultiCell($col5, $row1, 'No', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'トレーラー乗務', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'フェノール作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'フェリー乗船', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'クロスル作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'ドラム作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '洗浄作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'チェーン作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '他営業所応援', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '他部門応援', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'その他作業', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
			for($i=1;$i<=3;$i++){
				$pdf->MultiCell($col5, $row1, $i, '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, '回', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col5, $row1, '', '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			}

			$pdf->Ln(2);
			$pdf->MultiCell($col1, $row1, '荷主', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col6, $row1, '積地', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col6, $row1, '着地', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col12, $row1, '納入先', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col8, $row1, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col8, $row1, '数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col15, $row1, '積付状況', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
			for($i=1;$i<=7;$i++){
				$pdf->MultiCell($col1, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col6, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col6, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col12, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col8, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col8, $row4, ' ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
				$pdf->MultiCell($col15, $row4, ' ', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
			}

			$pdf->SetFontSize('8');
			$pdf->MultiCell(240, $row1, '※問題がなければ積付状況欄に✓印をつけてください。', '0', 'L', 0, 1, 10, 188, true, 0, false, true, $row2, 'M');
			$pdf->SetFontSize('10');
			$pdf->MultiCell(240, $row1, '上記の自身が記入した内容に間違いが無い事を証明致します。  正乗務員氏名　　　　　　　　　　　　　　　　　　　　副乗務員氏名', '0', 'L', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			$pdf->Line(110, 200, 285, 200);


			$count = 0;
			// ここから裏面
			$pdf->SetMargins(10, 5, 10, true);
			$pdf->SetAutoPageBreak(TRUE, 0);
			$pdf->AddPage('L','A4');

			$pdf->SetFontSize('16');
			$pdf->MultiCell(65, 7, '運　行　前　点　検　表', 'B', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell(87, 7, '', '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');

			$pdf->SetFontSize('12');
//			$pdf->MultiCell(60, 7, '運行管理者印', 'B', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
//			$pdf->MultiCell(5, 7, '', '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell(90, 7, '整備管理者印', 'B', 'L', 0, 1, 193, '', true, 0, false, true, 10, 'B');

			$pdf->Ln(4);

			$col2_1 = 8;
			$col2_2 = 36;
			$col2_3 = 36;
			$col2_4 = 12;

			$row2_1 = 8;
			$row2_2 = 6;

			$pdf->SetFontSize('8');
			$pdf->MultiCell($col2_1, $row2_2, '順序', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_2, $row2_2, '点検箇所', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_3, $row2_2, '点検項目', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_4, $row2_2, '了・否', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');

			$pdf->MultiCell($col2_1, $row2_2, '順序', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_2, $row2_2, '点検箇所', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_3, $row2_2, '点検項目', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_4, $row2_2, '了・否', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');

			$pdf->MultiCell($col2_1, $row2_2, '順序', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_2, $row2_2, '点検箇所', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_3, $row2_2, '点検項目', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_2, 'M');
			$pdf->MultiCell($col2_4, $row2_2, '了・否', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_2, 'M');

			// 2
			$pdf->MultiCell($col2_1, $row2_1*14, "運\n転\n席\nで", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*14, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' ※ 燃 料 装 置 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 燃 料 の 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1*15, "車\nの\n周\nり\nで", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*15, 'M');
			$pdf->MultiCell($col2_2, $row2_1*7, ' タ イ ヤ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1*7, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 空 気 圧 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1*7, "タ\nン\nク\n関\n係\nで", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*7, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' タ ン ク 本 体 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 傷 ・ 凹 み 等 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 3
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1*4, 'ブ レ ー キ ・ ペ ダ ル', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1*4, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 踏 み し ろ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 亀 裂 ・ 損 傷 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 安 全 弁 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 周 辺 の 滲 み ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 4
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, 'ブ レ ー キ の き き 具 合', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 異 常 な 摩 耗 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 吐 出 口 接 続 部 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ネ ジ 山 潰 れ ・ 変 形 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 5
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 片 ぎ き ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, "　金　属　片\n石　な　ど　の　異　物", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' バ ル ブ ハ ン ド ル ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '操作による甘さ・硬さ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 6
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '踏込み時の床板との隙間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ※ 溝 の 深 さ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 吐 出 口 バ ル ブ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, 'グランド部からの滲み', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 7
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 駐 車 ブ レ ー キ バ ー ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 引 き し ろ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, 'ホ イ ー ル ナ ッ ト 異 音', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' ポ ン プ 異 音 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 動 作 時 確 認 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 8
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1*2, ' 空 気 圧 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1*2, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '空 気 圧 の 上 が り 具 合', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, "ディスクホイールの\n取　り　付　け　状　態", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1*6, "ト\nレ\n|\nラ\n|\n関\n係\nで", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*6, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 車 両 下 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 漏 れ た 形 跡 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 9
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 空 気 圧 力 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1*2, "燈　火　装　置\n方　向　指　示　器", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*2, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 点 滅 具 合 ・ 汚 れ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, 'トラクタ作動油タンク', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 漏 れ 滲 み ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 10
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 警 音 器 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ク ラ ク シ ョ ン ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 損 傷 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1*2, 'トラクタ油圧配管・ホース', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*2, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '漏れ・滲み・亀裂・劣化', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 11
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, 'ウィンドウォッシャー', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 噴 霧 ・ 液 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 反 射 鏡 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 汚 れ ・ 損 傷 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ア イ マ ー ク の 一 致 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 12
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' ワ イ パ ー ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 拭 き 取 り 具 合 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '自 動 車 登 録 番 号 票', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, 'ト レ ー ラ ー 油 圧 配 管', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 漏 れ 滲 み ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 13
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, 'ブレーキのリザーバタンク', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 排 気 音 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 看 板 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '品 名 ・ 危 ・ 毒 表 示 等', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' ア ウ ト リ ガ ー ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' オ イ ル 漏 れ 滲 み ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 14
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 後 写 鏡 ・ 反 射 鏡 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 写 影 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 制 限 区 域 流 入 シ ー ル ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 大 阪 府 流 入 シ ー ル ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 作 動 用 電 源 ソ ケ ッ ト ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 劣 化 ・ 損 傷 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 15
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 座 席 ベ ル ト ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 取 付 具 合 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 車 両 下 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '路 面 に オ イ ル の 痕 跡', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1*3, "納\n入\n前", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*3, 'M');
			$pdf->MultiCell($col2_2+$col2_3, $row2_1, 'ア イ ド リ ン グ 状 態 で の オ イ ル 漏 れ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 16
			$pdf->MultiCell($col2_1, $row2_1*8, "エ\nン\nジ\nン\n・\nル\n|\nム\nで", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*8, 'M');
			$pdf->MultiCell($col2_2, $row2_1, 'ブレーキのリザーバタンク', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 液 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' エ ア ー タ ン ク ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' タ ン ク 内 の 凝 水 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2+$col2_3, $row2_1, ' ロ ー リ ー に 異 常 は な い か ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 17
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' エ ン ジ ン 本 体 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' かかり具合 ・ 異音 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1*7, "そ\nの\n他", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*7, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 温 度 計 ・ 運 行 記 録 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, 'チャート紙装着・作用', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2+$col2_3, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 18
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' エ ン ジ ン オ イ ル ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' 汚 れ ・ 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, "非　常　信　号　用　具\n停　止　装　置　板", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1*6, "備　付　の　有　無\n\n損　傷　の　有　無", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*6, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1+$col2_2+$col2_3+$col2_4, $row2_1, ' 良　レ 否　× ', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 19
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1*2, "ラヂエーターなどの\n冷　却　装　置", '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1*2, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ※ 水 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 車 検 証 ・ 保 険 証 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1+$col2_2+$col2_3+$col2_4, $row2_1, ' 前 日 に 於 け る 異 常 箇 所 ', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 20
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ※ 水 漏 れ ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 定 期 点 検 記 録 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1+$col2_2+$col2_3+$col2_4, $row2_1, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 21
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 潤 滑 装 置 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ※ エ ン ジ ン オ イ ル の 量 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 保 護 具 ・ 応 援 用 具 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1+$col2_2+$col2_3+$col2_4, $row2_1, ' 不 良 箇 所 及 び そ の 処 置 ', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 22
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' フ ァ ン ベ ル ト ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, ' ※ 張 り 具 合 ・ 損 傷 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, ' 歯 止 め ・ 消 火 器 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1+$col2_2+$col2_3+$col2_4, $row2_1, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			// 23
			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell($col2_1, $row2_1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_2, $row2_1, '緊急連絡・イエローカード', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_3, $row2_1, '', '0', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell($col2_4, $row2_1, '', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell(($col2_1+$col2_2+$col2_3+$col2_4)/2, $row2_1, ' 通 行 可 ・ 否 の 決 定 ', '1', 'J', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
			$pdf->MultiCell(($col2_1+$col2_2+$col2_3+$col2_4)/2, $row2_1, ' 可 ・ 否 ', '1', 'J', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

			$pdf->MultiCell('', '', '※印については高速走行（80K/H以上の走行）を行う場合特に点検すること。', '0', 'L', 0, 1, 15, '', true, 0, false, true, '', 'M');
$pdf->lastPage();
$pdf->Output('unkounittei.pdf', 'I');
