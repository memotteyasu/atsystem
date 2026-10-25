<?php
	echo $this->Form->create($model, ['url' => $url, 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

		echo $text;

		echo $this->Form->input($input, [
			'label' => false,
			'type' => 'select',
			'div' => false,
			'class' => 'form-control',
			'empty'=>'未選択',
			'onchange' => 'this.form.submit()',
			'data-rel' => 'chosen',
		]);

		echo '　　';

		echo $text2;

		echo $this->Form->input($input2, [
			'label' => false,
			'type' => 'select',
			'div' => false,
			'class' => 'form-control',
			'empty'=>'未選択',
			'onchange' => 'this.form.submit()',
			'data-rel' => 'chosen',
		]);

	echo $this->Form->end()
?>