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
		echo 'を表示　';

		echo $this->Form->button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>'.__(' 前日'), [
			'div' => false,
			'type' => 'submit',
			'class' => 'btn btn-primary',
			'name' => 'last1day',
		]);

		echo ' ';

		echo $this->Form->button(__('翌日 ').'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', [
			'div' => false,
			'type' => 'submit',
			'class' => 'btn btn-primary',
			'name' => 'next1day',
		]);

	echo '　';
	echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
	echo $this->Form->end();
?>
