<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('拘束時間一覧');
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
$pdf->SetAutoPageBreak(TRUE, 5);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();

	// 印刷日時
	$printday = new DateTime();

	// 期間を設定
  $interval = new DateInterval('P1M');
  $period = new DatePeriod($paramsDate1, $interval, $paramsDate2);

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

  // セルの幅設定
	$col1 = 24;
	$col2 = 32;
	$col3 = 28;
	$col4 = 30;

  // セルの高さ設定
	$row1 = 10;

  // 改ページ用変数
  $maxRowsPerPage = 24;
  $currentRow = 0;

  // 改ページ処理時用ヘッダー部分
  function headerRow($pdf, $printday, $userData, $col1, $col2, $col3, $col4, $row1, $paramsDate1, $period) {
    // タイトル部分ここから
    $pdf->SetFont('kozminproregular', '', 14);

    $pdf->Write(0, '拘束時間・時間外一覧表　'.$printday->format('Y年m月'), '', 0, 'L', true, 0, false, false, 0);

    $pdf->MultiCell(180, '', $userData['Departmentmaster']['departmentname'].' '.$userData['Branchmaster']['branchname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
    $pdf->MultiCell(200, '', 'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');
    // タイトル部分ここまで

    $pdf->Ln(4);

    // 見出しセルここから
    $pdf->SetFontSize(12);

    $pdf->MultiCell($col2, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');
    $pdf->MultiCell($col3, $row1, $paramsDate1->format('Y'), '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

    foreach ($period as $periodMonth) {
      $pdf->MultiCell($col1, $row1, "{$periodMonth->format('n')}月", '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
    }
    $pdf->MultiCell($col4, $row1, '合計', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
    // 見出しセルここまで
  }

  // ヘッダー部分を呼び出し
  headerRow($pdf, $printday, $userData, $col1, $col2, $col3, $col4, $row1, $paramsDate1, $period);

  // 一定数以上の行数になったら改ページ処理
  if ($currentRow >= $maxRowsPerPage) {
    // 改ページ処理
    $pdf->AddPage();
    // 現在行数をリセット
    $currentRow = 0;
    // ヘッダーを呼び出し
    headerRow($pdf, $printday, $userData, $col1, $col2, $col3, $col4, $row1, $paramsDate1, $period);
  }

  // ユーザー名部分
  $pdf->SetFillColor(230, 230, 230);
  $pdf->MultiCell($col2, $row1, $userData['User']['usernameK'], '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'M');
  $pdf->MultiCell($col3, $row1, '拘束:3400', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');

  // ここから拘束行
  $kousokuTimeTotal = '00:00';
  foreach ($period as $periodMonth) {
    // Kousokutranデータの存在チェック
    if ($userData['Kousokutran']) {
      $kousokuTime = '';
      foreach ($userData['Kousokutran'] as $kt) {
        if ($kt['kousokudate'] == $periodMonth->format('Y-m-d')) {
          $kousokuTime = substr($kt['kousokutime'], 0, -3);
          $kousokuTimeTotal = $this->Format->Addtime($kousokuTimeTotal, $kt['kousokutime']);
          break;
        }
      }
      $pdf->MultiCell($col1, $row1, $kousokuTime, '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
    } else {
      // 空セル
      $pdf->MultiCell($col1, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
    }
  }
  // 合計
  $pdf->MultiCell($col4, $row1, $kousokuTimeTotal, '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');
  $currentRow++;
  // ここまで拘束行

  // ここから時間外行
  $pdf->SetFillColor(255, 255, 255);
  $pdf->MultiCell($col2, $row1, '', '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'M');
  $pdf->MultiCell($col3, $row1, '時間外:960', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');

  $kousokuZangyoTimeTotal = '00:00';
  foreach ($period as $periodMonth) {
    // Kousokutranデータの存在チェック
    if ($userData['Kousokutran']) {
      $kousokuZangyoTime = '';
      foreach ($userData['Kousokutran'] as $kt) {
        if ($kt['kousokudate'] == $periodMonth->format('Y-m-d')) {
          $kousokuZangyoTime = substr($kt['kousokuzangyotime'], 0, -3);
          $kousokuZangyoTimeTotal = $this->Format->Addtime($kousokuZangyoTimeTotal, $kt['kousokuzangyotime']);
          break;
        }
      }
      $pdf->MultiCell($col1, $row1, $kousokuZangyoTime, '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
    } else {
      // 空セル
      $pdf->MultiCell($col1, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
    }
  }
  // 合計
  $pdf->MultiCell($col4, $row1, $kousokuZangyoTimeTotal, '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');
  $currentRow++;
  // ここまで時間外行

$pdf->lastPage();
$pdf->Output('kousoku.pdf', 'I');
