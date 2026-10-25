<?php
	echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

	echo $this->Form->input('selectym1', [
		'div'=>false,
		'label'=>false,
		'type' => 'text',
		'class' => 'datepicker1',
//		'type' => 'date',
//		'dateFormat' => 'YMD',
//		'monthNames' => false,
//		'minYear' => date('Y')-1,
//		'maxYear' => date('Y')+1,
		'onchange'=>'this.form.submit()',
			'default' => $paramsDate,
//		'data-rel'=>'chosen',
//		'style'=>'width:70px',
	]);

	echo $text;
	echo '　　営業所　';

	echo $this->Form->input('branchmasters_id',[
		'label'=>false,
		'type'=>'select',
		'div'=>false,
		'class'=>'form-control',
		'onchange'=>'this.form.submit()',
		'default' => $branchmasters_id,
	]);

	echo '　　承認　';

	echo $this->Form->input('check1flg',[
		'label' => false,
		'type' => 'select',
		'div' => false,
		'class' => 'form-control',
		'onchange' => 'this.form.submit()',
		'options'=>$checkarray,
		'data-rel' => 'chosen',
	]);

	echo $this->Form->end();
?>