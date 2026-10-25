<?php
	echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

	echo $this->Form->input('selectym1', [
		'div'=>false,
		'label'=>false,
		'type' => 'text',
		'class' => 'datepicker1 form-control',
		'onchange'=>'this.form.submit()',
		'default' => $paramsDate,
	]);

	echo $text;

	echo $this->Form->end();
?>