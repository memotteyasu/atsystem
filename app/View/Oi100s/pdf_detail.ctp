<?php
/*
 * 請求明細書（鏡とは別ページ）
 */
App::import('Vendor', 'tcpdf/tcpdf');

$isStandaloneDetailPdf = !isset($pdf);
if ($isStandaloneDetailPdf) {
	$pdf = new TCPDF('T', PDF_UNIT, 'A4', true, 'UTF-8', false);
	$pdf->SetMargins(10, 10, 8);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Asahi');
	$pdf->SetTitle('Invoice detail');
	$pdf->SetSubject('TCPDF');
	$pdf->SetKeywords('Asahi, invoice, detail');
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetAutoPageBreak(false);
	$pdf->SetFont('kozminproregular', '', 10);
}

$pageNumber = 0;
$totalPages = count($detailBranches);
foreach ($detailBranches as $detailBranch) {
	$pageNumber++;
	$details = $detailBranch['details'];
	foreach ($details as &$detail) {
		// Oi100sの従来明細はpayment、車両請求形式の明細はpriceに金額を保持する。
		$detail['display_amount'] = !empty($detail['payment']) ? (int)$detail['payment'] : (int)$detail['price'];
	}
	unset($detail);

	$totalFare = 0;
	$totalToll = 0;
	$totalHandToll = 0;
	foreach ($details as $detail) {
		$totalFare += $detail['display_amount'];
		$totalToll += (int)$detail['tollfee'];
		$totalHandToll += (int)$detail['handtollfee'];
	}
	$totalTolls = $totalToll + $totalHandToll;
	$fareExcludingTolls = $totalFare - $totalTolls;
	$consumptionTax = (int)floor($fareExcludingTolls * 0.1);
	$totalPayment = $totalTolls + $fareExcludingTolls + $consumptionTax;
	$detailTitle = $detailBranch['label'] === '本社（朝日運輸）'
		? '朝日運輸（株） 自動車運賃明細'
		: $detailBranch['label'];
	$commissionRate = isset($detailBranch['commission_rate']) ? $detailBranch['commission_rate'] : null;
	$commission = $commissionRate === null ? null : (int)floor($fareExcludingTolls * $commissionRate);
	$commissionTax = $commission === null ? null : (int)floor($commission * 0.1);
	$netPayment = $commission === null ? null : $totalPayment - $commission - $commissionTax;

	$pdf->AddPage();
	$pdf->SetFillColor(30, 144, 255);
	// 各ページで同じ位置・大きさのヘッダーラインを描画する。
	$pdf->Rect(10, 10, 190, 1, 'F');
	$pdf->SetFont('kozminproregular', '', 12);
	$pdf->Text(10, 16, $invoices['Invoice']['subject'].' ご請求書');
	$pdf->SetFontSize(16);
	$pdf->Text(10, 23, $detailTitle);
	$pdf->SetFontSize(9);
	$pdf->Text(145, 16, '請求書No：'.$invoices['Invoice']['invoicenumber']);
	$pdf->Text(145, 21, '発行日：'.$invoices['Invoice']['billingdate']);
	$pdf->Text(177, 31, '(単位：円)');

	$y = 35;
	$pdf->SetFontSize(11);
	$pdf->MultiCell(55, 10, '項目', 1, 'C', 0, 0, 10, $y, true, 0, false, true, 10, 'M');
	$pdf->MultiCell(35, 10, '計算式', 1, 'C', 0, 0, 65, $y, true, 0, false, true, 10, 'M');
	$pdf->MultiCell(35, 10, '金額', 1, 'C', 0, 0, 100, $y, true, 0, false, true, 10, 'M');
	$pdf->MultiCell(70, 5, '＊ 通行料内訳', 1, 'C', 0, 1, 135, $y, true, 0, false, true, 5, 'M');
	$pdf->MultiCell(35, 5, '通行料', 1, 'C', 0, 0, 135, $y + 5, true, 0, false, true, 5, 'M');
	$pdf->MultiCell(35, 5, '手書通行料', 1, 'C', 0, 1, 170, $y + 5, true, 0, false, true, 5, 'M');

	$y += 10;
	$pdf->SetFontSize(9);
	foreach ($details as $detail) {
		$pdf->MultiCell(55, 7, $detail['itemname'], 1, 'L', 0, 0, 10, $y, true, 0, false, true, 7, 'M');
		$quantity = (float)$detail['quantity'];
		// 数量が0の明細は計算式を印字しない。
		$calculation = $quantity == 0
			? ''
			: (($quantity != 1 || !empty($detail['unit']))
				? rtrim(rtrim(number_format($quantity, 2, '.', ''), '0'), '.').' '.$detail['unit']
				: '');
		$pdf->MultiCell(35, 7, $calculation, 1, 'C', 0, 0, 65, $y, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(35, 7, number_format($detail['display_amount']), 1, 'R', 0, 0, 100, $y, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(35, 7, number_format((int)$detail['tollfee']), 1, 'R', 0, 0, 135, $y, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(35, 7, number_format((int)$detail['handtollfee']), 1, 'R', 0, 1, 170, $y, true, 0, false, true, 7, 'M');
		$y += 7;
	}

		for ($emptyRows = count($details); $emptyRows < 10; $emptyRows++) {
			foreach ([55, 35, 35, 35, 35] as $column => $width) {
				$pdf->MultiCell($width, 7, '', 1, 'L', 0, $column === 4 ? 1 : 0, [10, 65, 100, 135, 170][$column], $y, true, 0, false, true, 7, 'M');
			}
			$y += 7;
		}

		$pdf->SetFontSize(10);
		$summaryRows = [
			['通行料込運賃（A）', '', $totalFare, '', ''],
			['＊ 通行料（B）', '', $totalTolls, $totalToll, $totalHandToll],
			['通行料抜運賃（C）', '（A）－（B）', $fareExcludingTolls, '', ''],
			['消費税（D）', '（C）＊10%', $consumptionTax, '', ''],
			['消費税込運賃（E）', '（B）＋（C）＋（D）', $totalPayment, '', ''],
		];
		if ($commission !== null) {
			$commissionPercent = number_format($commissionRate * 100, 2).'%';
			$summaryRows[] = ['手数料（F）', '（C）＊'.$commissionPercent, $commission, '', ''];
			$summaryRows[] = ['手数料消費税（G）', '（F）＊10%', $commissionTax, '', ''];
			$summaryRows[] = ['差引支払運賃（H）', '（E）－（F）－（G）', $netPayment, '', ''];
		} else {
			$summaryRows[] = ['支払運賃（F）', '（E）', $totalPayment, $totalToll, $totalHandToll];
		}
		foreach ($summaryRows as $row) {
			$pdf->MultiCell(55, 7, $row[0], 1, 'L', 0, 0, 10, $y, true, 0, false, true, 7, 'M');
			$pdf->MultiCell(35, 7, $row[1], 1, 'C', 0, 0, 65, $y, true, 0, false, true, 7, 'M');
			$pdf->MultiCell(35, 7, number_format($row[2]), 1, 'R', 0, 0, 100, $y, true, 0, false, true, 7, 'M');
			$pdf->MultiCell(35, 7, $row[3] === '' ? '' : number_format($row[3]), 1, 'R', 0, 0, 135, $y, true, 0, false, true, 7, 'M');
			$pdf->MultiCell(35, 7, $row[4] === '' ? '' : number_format($row[4]), 1, 'R', 0, 1, 170, $y, true, 0, false, true, 7, 'M');
			$y += 7;
		}
	$pdf->SetFontSize(8);
	$pdf->Text(180, 202, $pageNumber.' / '.$totalPages);
}

if ($isStandaloneDetailPdf) {
	$pdf->lastPage();
	$pdf->Output('invoice_detail.pdf', 'I');
}
