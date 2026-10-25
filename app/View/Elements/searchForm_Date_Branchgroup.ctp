<?php
	echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

	echo $this->Form->input('selectym1', [
		'div'=>false,
		'label'=>false,
		'type' => 'text',
		'class' => 'datepicker1',
		//'onchange'=>'this.form.submit()',
		'default' => $paramsDate,
	]);

	echo $text;
	echo '　　営業所　';

	echo $this->Form->input('branchgroups_id',[
		'label'=>false,
		'type'=>'select',
		'div'=>false,
		'class'=>'form-control',
		//'onchange'=>'this.form.submit()',
		'default' => $branchgroups_id,
	]);
	echo '　　';

	echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
	echo $this->Form->end();
?>