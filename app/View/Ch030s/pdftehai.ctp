<?php
App::import('Vendor', 'tcpdf/tcpdf');

// 現場で使用している「化学品 手配明細」と同じ、縦向き・車両単位の帳票。
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(5, 7, 5);
$pdf->SetAutoPageBreak(false, 5);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('化学品 手配明細');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$date = new DateTime($this->params['pass']['0']);
$nowdate = new DateTime();
$weekName = isset($week[$date->format('w')]) ? $week[$date->format('w')] : '';
$lineHeight = 7;

// 合計 200mm（A4縦、左右余白各5mm）。紙帳票と同様に車両ごとに明細をまとめる。
$columns = array(
    'driver' => 18,
    'car' => 22,
    'workplace' => 34,
    'work' => 27,
    'reference' => 18,
    'time' => 8,
    'assistant' => 15,
    'next' => 40,
    'note' => 18,
);

$renderHeader = function($pdf) use ($date, $nowdate, $weekName, $columns, $lineHeight) {
    $pdf->SetLineWidth(0.2);
    $pdf->SetFont('kozminproregular', '', 13);
    $pdf->Cell(200, 7, '化　学　品　手　配　明　細', 0, 1, 'C');

    $pdf->SetFont('kozminproregular', '', 8);
    $pdf->Cell(75, 5, '配車日：'.$date->format('Y年m月d日').'（'.$weekName.'）', 0, 0, 'L');
    $pdf->Cell(65, 5, '', 0, 0, 'C');
    $pdf->Cell(60, 5, '作成日：'.$nowdate->format('Y年m月d日 H:i').'　P.'.$pdf->getPage(), 0, 1, 'R');

    $pdf->SetFillColor(235, 235, 235);
    $pdf->SetFont('kozminproregular', '', 8);
    $pdf->MultiCell($columns['driver'], $lineHeight, '乗務員', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['car'], $lineHeight, '車番', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['workplace'], $lineHeight, '荷主・作業場所', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['work'], $lineHeight, '品名・作業', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['reference'], $lineHeight, '容器No.・Ref No.', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['time'], $lineHeight, '卸', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['assistant'], $lineHeight, '助手', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['next'], $lineHeight, '次便予定', 1, 'C', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
    $pdf->MultiCell($columns['note'], $lineHeight, '備考', 1, 'C', true, 1, '', '', true, 0, false, true, $lineHeight, 'M');
};

$newPage = function($pdf) use ($renderHeader) {
    $pdf->AddPage();
    $renderHeader($pdf);
};

$newPage($pdf);
$lastCarId = null;

// drtops と同じ主乗務員の予定工程から、各オーダーの最初の工程を作る。
// 同一オーダー内の後続工程は「次便」には含めない。
$driverOrderSchedules = array();
foreach ($driverNextOrders as $nextOrderProcess) {
    $nextProcess = $nextOrderProcess['Processtran'];
    $driverId = $nextProcess['pdriver1id'];
    $nextOrderId = $nextProcess['ordertrans_id'];
    if (empty($driverId) || empty($nextOrderId) || isset($driverOrderSchedules[$driverId][$nextOrderId])) {
        continue;
    }
    $driverOrderSchedules[$driverId][$nextOrderId] = $nextProcess;
    $driverOrderSchedules[$driverId][$nextOrderId]['kaisen'] = isset($nextOrderProcess['Ordertran']['kaisen']) ? $nextOrderProcess['Ordertran']['kaisen'] : null;
    $driverOrderSchedules[$driverId][$nextOrderId]['workname'] = !empty($nextOrderProcess['Workdetailmaster']['workname']) ? $nextOrderProcess['Workdetailmaster']['workname'] : '';
    $driverOrderSchedules[$driverId][$nextOrderId]['workplace'] = !empty($nextOrderProcess['Workplace']['workplaceshortname']) ? $nextOrderProcess['Workplace']['workplaceshortname'] : '';
}

foreach ($ordertrans as $ordertran) {
    $carId = !empty($ordertran['Carmaster']['id']) ? $ordertran['Carmaster']['id'] : 0;
    $plateno = !empty($ordertran['Carmaster']['plateno']) ? $ordertran['Carmaster']['plateno'] : '未定';
    $plateDigits = preg_replace('/[^0-9]/', '', $plateno);
    $shortPlateno = $plateDigits !== '' ? substr($plateDigits, -4) : $plateno;
    $branchname = !empty($ordertran['Branchmaster']['branchname']) ? $ordertran['Branchmaster']['branchname'] : '';
    $shipper = !empty($ordertran['Shippermaster']['shippershortname']) ? $ordertran['Shippermaster']['shippershortname'] : '';

    // 車両マスタ displayno 順で取得された配列を崩さず、車両が切り替わる時だけ太線見出しを出す。
    if ($lastCarId !== $carId) {
        if ($pdf->GetY() + $lineHeight > 287) {
            $newPage($pdf);
        }
        $pdf->SetLineWidth(0.5);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetFont('kozminproregular', '', 8);
        $pdf->MultiCell($columns['driver'] + $columns['car'], $lineHeight, $plateno.'　'.$branchname, 1, 'L', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['workplace'] + $columns['work'], $lineHeight, '荷主：'.$shipper, 1, 'L', true, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['reference'] + $columns['time'] + $columns['assistant'] + $columns['next'] + $columns['note'], $lineHeight, '', 1, 'L', true, 1, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->SetLineWidth(0.2);
        $lastCarId = $carId;
    }

    foreach ($ordertran['Processtran'] as $processtran) {
        if (empty($processtran['processstartdate'])) {
            continue;
        }
        $workDate = new DateTime($processtran['processstartdate'].(!empty($processtran['processstarttime']) ? ' '.$processtran['processstarttime'] : ''));
        // 従来どおり配車日以降の工程のみを手配明細に印字する。
        if ($workDate->format('Y-m-d') < $date->format('Y-m-d')) {
            continue;
        }
        if ($pdf->GetY() + $lineHeight > 287) {
            $newPage($pdf);
        }

        $driver = !empty($processtran['PDriver1']['usernameK']) ? $processtran['PDriver1']['usernameK'] : '未定';
        $assistant = !empty($processtran['PDriver2']['usernameK']) ? $processtran['PDriver2']['usernameK'] : '';
        $workplace = !empty($processtran['Workplace']['workplaceshortname']) ? $processtran['Workplace']['workplaceshortname'] : '';
        $workname = !empty($processtran['Workdetailmaster']['workname']) ? $processtran['Workdetailmaster']['workname'] : '';
        $product = !empty($processtran['Productcontainer']['pdconname']) ? $processtran['Productcontainer']['pdconname'] : '';
        $productAndWork = trim($product."\n".$workname);
        $reference = trim((!empty($processtran['container_no']) ? $processtran['container_no'] : '')."\n".(!empty($processtran['ref_no']) ? $processtran['ref_no'] : ''));
        // 卸日は配車日と同日なら印字せず、翌日以降の卸しだけを識別できるようにする。
        $unloadingDate = !empty($ordertran['Ordertran']['deliverydate']) ? new DateTime($ordertran['Ordertran']['deliverydate']) : null;
        $unloading = ($unloadingDate && $unloadingDate->format('Y-m-d') !== $date->format('Y-m-d')) ? $unloadingDate->format('j') : '';
        $note = !empty($processtran['workddetailcomment']) ? $processtran['workddetailcomment'] : '';
        $nextPlan = '';
        $driverId = !empty($processtran['pdriver1id']) ? $processtran['pdriver1id'] : null;
        $currentProcessKey = $workDate->format('Y-m-d H:i:s');
        $currentKaisen = isset($ordertran['Ordertran']['kaisen']) ? $ordertran['Ordertran']['kaisen'] : null;
        $nextByKaisen = null;
        $nextByTime = null;
        if ($driverId && !empty($driverOrderSchedules[$driverId])) {
            foreach ($driverOrderSchedules[$driverId] as $nextOrderId => $nextProcess) {
                $nextProcessKey = $nextProcess['processstartdate'].' '.(!empty($nextProcess['processstarttime']) ? $nextProcess['processstarttime'] : '23:59:59');
                if ($nextOrderId == $ordertran['Ordertran']['id']) {
                    continue;
                }
                // 回戦が設定されていれば、同じ乗務員の次回戦を最優先にする。
                if (is_numeric($currentKaisen) && is_numeric($nextProcess['kaisen']) && $nextProcess['kaisen'] > $currentKaisen) {
                    if ($nextByKaisen === null || $nextProcess['kaisen'] < $nextByKaisen['kaisen'] || ($nextProcess['kaisen'] == $nextByKaisen['kaisen'] && $nextProcessKey < $nextByKaisen['key'])) {
                        $nextProcess['key'] = $nextProcessKey;
                        $nextByKaisen = $nextProcess;
                    }
                    continue;
                }
                // 回戦が未設定・同一の時だけ、従来の工程日時順を補助的に使う。
                if (($currentKaisen === null || $currentKaisen === '' || $nextProcess['kaisen'] === null || $nextProcess['kaisen'] === '') && $nextProcessKey >= $currentProcessKey) {
                    if ($nextByTime === null || $nextProcessKey < $nextByTime['key']) {
                        $nextProcess['key'] = $nextProcessKey;
                        $nextByTime = $nextProcess;
                    }
                }
            }
            $selectedNextProcess = $nextByKaisen ? $nextByKaisen : $nextByTime;
            if ($selectedNextProcess) {
                $nextTime = !empty($selectedNextProcess['processstarttime']) ? substr($selectedNextProcess['processstarttime'], 0, 5) : $selectedNextProcess['timenote'];
                $nextDate = new DateTime($selectedNextProcess['processstartdate']);
                $nextPlan = $nextDate->format('n/j').' '.$nextTime."\n".trim($selectedNextProcess['workname'].' '.$selectedNextProcess['workplace']);
            }
        }

        $pdf->MultiCell($columns['driver'], $lineHeight, $driver, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['car'], $lineHeight, $shortPlateno, 1, 'C', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['workplace'], $lineHeight, $workplace, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['work'], $lineHeight, $productAndWork, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['reference'], $lineHeight, $reference, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['time'], $lineHeight, $unloading, 1, 'C', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['assistant'], $lineHeight, $assistant, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['next'], $lineHeight, $nextPlan, 1, 'L', false, 0, '', '', true, 0, false, true, $lineHeight, 'M');
        $pdf->MultiCell($columns['note'], $lineHeight, $note, 1, 'L', false, 1, '', '', true, 0, false, true, $lineHeight, 'M');
    }
}

$pdf->lastPage();
$pdf->Output('tehai.pdf', 'I');
