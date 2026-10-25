<?php
	echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

		echo $this->Form->input('selectym1', [
			'div'=>false,
			'label'=>false,
					'type' => 'text',
					'class' => 'datepicker1',
//			'type' => 'date',
//			'dateFormat' => 'YMD',
//			'monthNames' => false,
//			'minYear' => date('Y')-1,
//			'maxYear' => date('Y')+1,
			'onchange'=>'this.form.submit()',
//			'data-rel'=>'chosen',
			'default' => $paramsDate,
//			'style'=>'width:70px',
		]);

		echo 'から1週間　';

		echo $this->Form->button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>'.__(' 前週'), [
			'div' => false,
			'type' => 'submit',
			'class' => 'btn btn-primary',
			'name' => 'lastweek',
		]);

		echo ' ';

		echo $this->Form->button(__('翌週 ').'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', [
			'div' => false,
			'type' => 'submit',
			'class' => 'btn btn-primary',
			'name' => 'nextweek',
		]);

		echo '　　';

		echo '荷主　';

		echo $this->Form->input('shippermasters_id',[
			'label'=>false,
			'type'=>'select',
			'empty'=>'未選択',
			'div'=>false,
			'class'=>'form-control',
			'onchange'=>'this.form.submit()',
//			'data-rel'=>'chosen',
			'default' => $shippermasters_id,
		]);

		echo '　　';
		echo '営業所　';

		echo $this->Form->input('branchmasters_id',[
			'label'=>false,
			'type'=>'select',
			'empty'=>'未選択',
			'div'=>false,
			'class'=>'form-control',
			'onchange'=>'this.form.submit()',
//			'data-rel'=>'chosen',
			'default' => $branchmasters_id,
		]);

		echo '　　';
		echo '商品　';

		echo $this->Form->input('productdetailmasters_id',[
			'label'=>false,
			'type'=>'select',
			'empty'=>'未選択',
			'div'=>false,
			'class'=>'form-control',
			'onchange'=>'this.form.submit()',
			'data-rel'=>'chosen',
			'default' => $productdetailmasters_id,
		]);

	echo $this->Form->end();
?>
